<?php
/**
 * Ancora Framework: attachment manipulations
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Theme init
if ( !function_exists( 'ancora_attachment_settings_theme_setup2' ) ) {
	add_action( 'ancora_action_before_init_theme', 'ancora_attachment_settings_theme_setup2', 3 );
	function ancora_attachment_settings_theme_setup2() {
		ancora_add_theme_inheritance( array('attachment' => array(
			'stream_template' => '',
			'single_template' => 'attachment',
			'taxonomy' => array(),
			'taxonomy_tags' => array(),
			'post_type' => array('attachment'),
			'override' => 'post'
			) )
		);
	}
}

if (!function_exists('ancora_attachment_theme_setup')) {
	add_action( 'ancora_action_before_init_theme', 'ancora_attachment_theme_setup');
	function ancora_attachment_theme_setup() {

		// Add folders in ajax query
		add_filter('ajax_query_attachments_args',				'ancora_attachment_ajax_query_args');

		// Add folders in filters for js view
		add_filter('media_view_settings',						'ancora_attachment_view_filters');

		// Add folders list in js view compat area
		add_filter('attachment_fields_to_edit',					'ancora_attachment_view_compat');

		// Prepare media folders for save
		add_filter( 'attachment_fields_to_save',				'ancora_attachment_save_compat');

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('ancora_filter_detect_inheritance_key',	'ancora_attachmnent_detect_inheritance_key', 9, 1);

		// Prepare taxonomy for attachment
		ancora_require_data( 'taxonomy', 'media_folder', array(
			'post_type'			=> array( 'attachment' ),
			'hierarchical' 		=> true,
			'labels' 			=> array(
				'name'              => __('Media Folders', 'ancora'),
				'singular_name'     => __('Media Folder', 'ancora'),
				'search_items'      => __('Search Media Folders', 'ancora'),
				'all_items'         => __('All Media Folders', 'ancora'),
				'parent_item'       => __('Parent Media Folder', 'ancora'),
				'parent_item_colon' => __('Parent Media Folder:', 'ancora'),
				'edit_item'         => __('Edit Media Folder', 'ancora'),
				'update_item'       => __('Update Media Folder', 'ancora'),
				'add_new_item'      => __('Add New Media Folder', 'ancora'),
				'new_item_name'     => __('New Media Folder Name', 'ancora'),
				'menu_name'         => __('Media Folders', 'ancora'),
			),
			'query_var'			=> true,
			'rewrite' 			=> true,
			'show_admin_column'	=> true
			)
		);
	}
}


// Add folders in ajax query
if (!function_exists('ancora_attachment_ajax_query_args')) {
	//add_filter('ajax_query_attachments_args', 'ancora_attachment_ajax_query_args');
	function ancora_attachment_ajax_query_args($query) {
		if (isset($query['post_mime_type'])) {
			$v = $query['post_mime_type'];
			if (ancora_substr($v, 0, 13)=='media_folder.') {
				unset($query['post_mime_type']);
				if (ancora_strlen($v) > 13)
					$query['media_folder'] = ancora_substr($v, 13);
				else {
					$list_ids = array();
					$terms = ancora_get_terms_by_taxonomy('media_folder');
					if (count($terms) > 0) {
						foreach ($terms as $term) {
							$list_ids[] = $term->term_id;
						}
					}
					if (count($list_ids) > 0) {
						$query['tax_query'] = array(
							array(
								'taxonomy' => 'media_folder',
								'field' => 'id',
								'terms' => $list_ids,
								'operator' => 'NOT IN'
							)
						);
					}
				}
			}
		}
		return $query;
	}
}

// Add folders in filters for js view
if (!function_exists('ancora_attachment_view_filters')) {
	//add_filter('media_view_settings', 'ancora_attachment_view_filters');
	function ancora_attachment_view_filters($settings, $post=null) {
		$taxes = array('media_folder');
		foreach ($taxes as $tax) {
			$terms = ancora_get_terms_by_taxonomy($tax);
			if (count($terms) > 0) {
				$settings['mimeTypes'][$tax.'.'] = __('Media without folders', 'ancora');
				$settings['mimeTypes'] = array_merge($settings['mimeTypes'], ancora_get_terms_hierarchical_list($terms, array(
					'prefix_key' => 'media_folder.',
					'prefix_level' => '-'
					)
				));
			}
		}
		return $settings;
	}
}

// Add folders list in js view compat area
if (!function_exists('ancora_attachment_view_compat')) {
	//add_filter('attachment_fields_to_edit', 'ancora_attachment_view_compat');
	function ancora_attachment_view_compat($form_fields, $post=null) {
		static $terms = null, $id = 0;
		if (isset($form_fields['media_folder'])) {
			$field = $form_fields['media_folder'];
			if (!$terms) {
				$terms = ancora_get_terms_by_taxonomy('media_folder', array(
					'hide_empty' => false
					));
				$terms = ancora_get_terms_hierarchical_list($terms, array(
					'prefix_key' => 'media_folder.',
					'prefix_level' => '-'
					));
			}
			$values = array_map('trim', explode(',', $field['value']));
			$readonly = ''; //! $user_can_edit && ! empty( $field['taxonomy'] ) ? " readonly='readonly' " : '';
			$required = !empty($field['required']) ? '<span class="alignright"><abbr title="required" class="required">*</abbr></span>' : '';
			$aria_required = !empty($field['required']) ? " aria-required='true' " : '';
			$html = '';
			if (count($terms) > 0) {
				foreach ($terms as $slug=>$name) {
					$id++;
					$slug = ancora_substr($slug, 13);
					$html .= ($html ? '<br />' : '') . '<input type="checkbox" class="text" id="media_folder_'.esc_attr($id).'" name="media_folder_' . esc_attr($slug) . '" value="' . esc_attr( $slug ) . '"' . (in_array($slug, $values) ? ' checked="checked"' : '' ) . ' ' . ($readonly) . ' ' . ($aria_required) . ' /><label for="media_folder_'.esc_attr($id).'"> ' . ($name) . '</label>';
				}
			}
			$form_fields['media_folder']['input'] = 'media_folder_input';
			$form_fields['media_folder']['media_folder_input'] = '<div class="media_folder_selector">' . ($html) . '</div>';
		}
		return $form_fields;
	}
}

// Prepare media folders for save
if (!function_exists('ancora_attachment_save_compat')) {
	//add_filter( 'attachment_fields_to_save', 'ancora_attachment_save_compat');
	function ancora_attachment_save_compat($post=null, $attachment_data=null) {
		if (!empty($post['ID']) && ($id = intval($post['ID'])) > 0) {
			$folders = array();
			$from_media_library = !empty($_REQUEST['tax_input']['media_folder']) && is_array($_REQUEST['tax_input']['media_folder']);
			// From AJAX query
			if (!$from_media_library) {
				foreach ($_REQUEST as $k => $v) {
					if (ancora_substr($k, 0, 12)=='media_folder')
						$folders[] = $v;
				}
			} else {
				if (count($folders)==0) {
					if (!empty($_REQUEST['tax_input']['media_folder']) && is_array($_REQUEST['tax_input']['media_folder'])) {
						foreach ($_REQUEST['tax_input']['media_folder'] as $k => $v) {
							if ((int)$v > 0)
								$folders[] = $v;
						}
					}
				}
			}
			if (count($folders) > 0) {
				foreach ($folders as $k=>$v) {
					if ((int) $v > 0) {
						$term = get_term_by('id', $v, 'media_folder');
						$folders[$k] = $term->slug;
					}
				}
			} else
				$folders = null;
			// Save folders list only from AJAX
			if (!$from_media_library)
				wp_set_object_terms( $id, $folders, 'media_folder', false );
		}
		return $post;
	}
}


// Filter to detect current page inheritance key
if ( !function_exists( 'ancora_attachmnent_detect_inheritance_key' ) ) {
	//add_filter('ancora_filter_detect_inheritance_key',	'ancora_attachmnent_detect_inheritance_key', 9, 1);
	function ancora_attachmnent_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return is_attachment() ? 'attachment' : '';
	}
}
?>
