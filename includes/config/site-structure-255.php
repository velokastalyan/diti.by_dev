<?php
$ss =
array(
	'title' => 'Art-cms', 'short-title' => 'Art-cms', 'mode' => 'hidden',  'tag' => '', 'class' => 'admin/IndexPage.php', 'template' => 'admin/index.tpl', 'children' => array(
		0 => array(
			'title' => 'Login', 'tag' => 'login', 'mode' => 'hidden', 'class' => 'admin/LoginPage.php', 'template' => 'admin/login.tpl', 'children' => array(
			),
		),
		1 => array(
			'title' => 'Users', 'tag' => 'users', 'class' => 'admin/users/UsersPage.php', 'template' => 'admin/users/users.tpl', 'children' => array(
                0 => array(
                        'title' => 'User edit', 'tag' => 'user_edit', 'mode' => 'hidden', 'class' => 'admin/users/UserEditPage.php', 'template' => 'admin/users/user_edit.tpl'
                ),
				1 => array(
					'title' => 'User roles', 'tag' => 'roles', 'class' => 'admin/users/RolesPage.php', 'template' => 'admin/users/roles.tpl', 'children' => array(
		                0 => array(
		                    'title' => 'Role add/edit', 'tag' => 'user_role_edit', 'class' => 'admin/users/UserRoleEditPage.php', 'template' => 'admin/users/user_role_edit.tpl', 'mode' => 'hidden'
		            	),
					),
				),
				/** Deprecated
				2 => array(
					'title' => 'Social Network', 'tag' => 'social', 'class' => 'admin/users/social/SocialNetworkPage.php', 'template' => 'admin/users/social/social_network.tpl', 'children' => array(
		                0 => array(
		                	'title' => 'VK users', 'tag' => 'vk', 'class' => 'admin/users/social/VkUsersPage.php', 'template' => 'admin/users/social/vk_users.tpl', 'children' => array(
		                		0 => array(
		                			'title' => 'VK User edit', 'tag' => 'vk_user_edit', 'class' => 'admin/users/social/VkUserEditPage.php', 'template' => 'admin/users/social/vk_user_edit.tpl'
		                		),
		                	),
		            	),
		            	1 => array(
		                    'title' => 'FB users', 'tag' => 'fb', 'class' => 'admin/users/social/FbUsersPage.php', 'template' => 'admin/users/social/fb_users.tpl', 'children' => array(
		                		0 => array(
		                			'title' => 'FB User edit', 'tag' => 'fb_user_edit', 'class' => 'admin/users/social/FbUserEditPage.php', 'template' => 'admin/users/social/fb_user_edit.tpl'
		                		),
		                	),
		            	),
					),
				),
				*/
			),
		),
		2 => array(
			'title' => 'Localizer strings', 'tag' => 'localizer_strings', 'class' => 'admin/localizer/StringsPage.php', 'template' => 'admin/localizer/strings.tpl', 'children' => array(
                0 => array(
                    'title' => 'Localizer string view', 'tag' => 'loc_string_edit', 'mode' => 'hidden', 'class' => 'admin/localizer/StringEditPage.php', 'template' => 'admin/localizer/string_edit.tpl', 'children' => array(
                	),
            	),
				1 => array(
					'title' => 'Languages', 'tag' => 'languages', 'class' => 'admin/localizer/LanguagesPage.php', 'template' => 'admin/localizer/languages.tpl', 'children' => array(
		                0 => array(
		                        'title' => 'Language edit', 'tag' => 'loc_lang_edit', 'mode' => 'hidden', 'class' => 'admin/localizer/LanguageEditPage.php', 'template' => 'admin/localizer/language_edit.tpl'
		            	),
					),
				),
			),
		),
		3 => array(
			'title' => 'Настройки', 'tag' => 'registry', 'class' => 'admin/registry/RegistryPage.php', 'template' => 'admin/registry/_registry_out.tpl', 'children' => array(
				0 => array(
					'title' => 'Images', 'tag' => 'image', 'class' => 'admin/registry/ImagePage.php', 'template' => 'admin/registry/image.tpl', 'children' => array(
						0 => array(
								'title' => 'Image view', 'tag' => 'image_edit', 'class' => 'admin/registry/ImageEditPage.php', 'template' => 'admin/registry/image_edit.tpl', 'children' => array(
								0 => array(
										'title' => 'Image size view', 'tag' => 'image_size_edit', 'class' => 'admin/registry/ImageSizeEditPage.php', 'template' => 'admin/registry/image_size_edit.tpl'
								),
							),
						),
					),
				),
				1 => array(
					'title' => 'Registry Path edit', 'tag' => 'path_edit', 'class' => 'admin/registry/PathEditPage.php', 'template' => 'admin/registry/path_edit.tpl', 'mode' => 'hidden', 'children' => array(
						0 => array(
							'title' => 'Path Value edit', 'tag' => 'path_value_edit', 'class' => 'admin/registry/PathValueEditPage.php', 'template' => 'admin/registry/path_value_edit.tpl'
						),
					),
				),
                            2 => array(
					'title' => 'Меню', 'tag' => 'menus', 'class' => 'admin/options/MenusPage.php', 'template' => 'admin/options/menus.tpl', 'children' => array(
						0 => array(
							'title' => 'Редактирование меню', 'tag' => 'menus_edit', 'class' => 'admin/options/MenuEditPage.php', 'template' => 'admin/options/menu_edit.tpl', 'mode' => 'hidden',
						),
					),
				),
			),
                    
		),
		4 => array(
			'title' => 'Страницы', 'tag' => 'pages', 'class' => 'admin/pages/PagesPage.php', 'template' => 'admin/pages/pagespage.tpl', 'children' => array(
				0 => array(
					'title' => 'Редактирование страниц', 'tag' => 'page_edit', 'mode' => 'hidden', 'class' => 'admin/pages/PageEditPage.php', 'template' => 'admin/pages/page_edit.tpl'
				),
			),
		),

		5 => array(
			'title' => 'Статьи', 'tag' => 'article', 'class' => 'admin/article/ArticlePage.php', 'template' => 'admin/article/article.tpl', 'children' => array(

				0 => array(
					'title' => 'Редактирование статей', 'tag' => 'article_edit', 'mode' => 'hidden', 'class' => 'admin/article/ArticleEditPage.php', 'template' => 'admin/article/article_edit.tpl', 'children' => array(

					),
				),

				1 => array(
					'title' => 'Категории', 'tag' => 'article_category', 'class' => 'admin/article/ArticleCategoryPage.php', 'template' => 'admin/article/articlecategory.tpl', 'children' => array(

						0 => array(
							'title' => 'Редактирование категорий', 'tag' => 'article_category_edit', 'mode' => 'hidden', 'class' => 'admin/article/ArticleCategoryEditPage.php', 'template' => 'admin/article/articlecategory_edit.tpl', 'children' => array(

							),
						),
					),
				),

				2 => array(
					'title' => 'Комментарии', 'tag' => 'article_comment', 'class' => 'admin/article/ArticleCommentPage.php', 'template' => 'admin/article/articlecomment.tpl', 'children' => array(

						0 => array(
							'title' => 'Редактирование комментариев', 'tag' => 'article_comment_edit', 'mode' => 'hidden', 'class' => 'admin/article/ArticleCommentEditPage.php', 'template' => 'admin/article/articlecomment_edit.tpl', 'children' => array(

							),
						),
					),
				),

			),
		),

		6 => array(
			'title' => 'Продукция', 'tag' => 'production', 'class' => 'admin/production/ProductionPage.php', 'template' => 'admin/production/production.tpl', 'children' => array(

				0 => array(
					'title' => 'Продукты', 'tag' => 'product', 'class' => 'admin/production/ProductPage.php', 'template' => 'admin/production/product.tpl', 'children' => array(

						0 => array(
							'title' => 'Редактирование продуктов', 'tag' => 'product_edit',  'mode' => 'hidden', 'class' => 'admin/production/ProductEditPage.php', 'template' => 'admin/production/product_edit.tpl', 'children' => array(

								0 => array(

									'title' => 'Редактирование Изображения', 'tag' => 'image_edit', 'class' => 'admin/production/ImageEditPage.php', 'template' => 'admin/production/image_edit.tpl', 'mode' => 'hidden'

										),
								1 => array(

									'title' => 'Редактирование рекоммендуемой продукции', 'tag' => 'recommend_edit', 'class' => 'admin/production/RecommendEditPage.php', 'template' => 'admin/production/recommend_edit.tpl', 'mode' => 'hidden'

										),

									),
								),

							),
						),

				1 => array(
					'title' => 'Категории', 'tag' => 'category', 'class' => 'admin/category/CategoryPage.php', 'template' => 'admin/category/category.tpl', 'children' => array(

						0 => array(
						'title' => 'Редактирование категорий', 'tag' => 'category_edit', 'mode' => 'hidden', 'class' => 'admin/category/CategoryEditPage.php', 'template' => 'admin/category/category_edit.tpl', 'children' => array(

							),
						),

					),
				),

				2 => array(
					'title' => 'Бренды', 'tag' => 'brand', 'class' => 'admin/brand/BrandPage.php', 'template' => 'admin/brand/brand.tpl', 'children' => array(

						0 => array(
							'title' => 'Редактирование брендов', 'tag' => 'brand_edit', 'mode' => 'hidden', 'class' => 'admin/brand/BrandEditPage.php', 'template' => 'admin/brand/brand_edit.tpl', 'children' => array(

							),
						),

					),
				),
                3 => array(
                    'title' => 'Экспорт в YML', 'tag' => 'yml', 'class' => 'admin/yml/YmlPage.php', 'template' => 'admin/yml/yml.tpl', 'children' => array(
                    ),
                ),
			),
		),

		8 => array(
			'title' => 'Отзывы', 'tag' => 'comment', 'class' => 'admin/comment/CommentPage.php', 'template' => 'admin/comment/comment.tpl', 'children' => array(

				0 => array(
					'title' => 'Редактирование отзывов', 'tag' => 'comment_edit', 'mode' => 'hidden','class' => 'admin/comment/CommentEditPage.php', 'template' => 'admin/comment/comment_edit.tpl', 'children' => array(

					),
				),
			),
		),

		9 => array(
			'title' => 'Баннеры', 'tag' => 'banner', 'class' => 'admin/banner/BannerPage.php', 'template' => 'admin/banner/banner.tpl', 'children' => array(

				0 => array(
					'title' => 'Редактирование баннеров', 'tag' => 'banner_edit', 'mode' => 'hidden','class' => 'admin/banner/BannerEditPage.php', 'template' => 'admin/banner/banner_edit.tpl', 'children' => array(

					),
				),
			),
		),

		10 => array(
			'title' => 'Видео', 'tag' => 'video', 'class' => 'admin/video/VideoPage.php', 'template' => 'admin/video/video.tpl', 'children' => array(

				0 => array(
					'title' => 'Редактирование видео', 'tag' => 'video_edit', 'mode' => 'hidden','class' => 'admin/video/VideoEditPage.php', 'template' => 'admin/video/video_edit.tpl', 'children' => array(

					),
				),
			),
		),
		11 => array(
			'title' => 'Подвал сайта', 'tag' => 'brandfooter', 'class' => 'admin/brand/BrandFooterPage.php', 'template' => 'admin/brand/brandfooter.tpl', 'children' => array(

				0 => array(
					'title' => 'Редактирование подвала сайта', 'tag' => 'brand_footer_edit', 'mode' => 'hidden','class' => 'admin/brand/BrandFooterEditPage.php', 'template' => 'admin/brand/brand_footer_edit.tpl', 'children' => array(

					),
				),
			),
		),
		12 => array(
			'title' => 'Консультации', 'tag' => 'consultation', 'class' => 'admin/consultation/ConsultationPage.php', 'template' => 'admin/consultation/consultation.tpl', 'children' => array(

				0 => array(
					'title' => 'Редактирование консультации', 'tag' => 'consultation_edit', 'mode' => 'hidden','class' => 'admin/consultation/ConsultationEditPage.php', 'template' => 'admin/consultation/consultation_edit.tpl', 'children' => array(

					),
				),
			),
		),
        13 => array(
            'title' => 'Сервис', 'tag' => 'service', 'class' => 'admin/service/ServicePage.php', 'template' => 'admin/service/service.tpl', 'children' => array(

                0 => array(
                    'title' => 'Редактирование сервисов', 'tag' => 'service_edit', 'mode' => 'hidden','class' => 'admin/service/ServiceEditPage.php', 'template' => 'admin/service/service_edit.tpl', 'children' => array(

                    ),
                ),
            ),
        ),
        14 => array(
            'title' => 'Рассылка', 'tag' => 'distribution', 'class' => 'admin/distribution/DistributionPage.php', 'template' => 'admin/distribution/distribution.tpl', 'children' => array(
                0 => array(
                    'title' => 'Редактирование письма', 'tag' => 'distribution_edit', 'mode' => 'hidden', 'class' => 'admin/distribution/DistributionEditPage.php', 'template' => 'admin/distribution/distribution_edit.tpl'
                ),
                1 => array(
                    'title' => 'Подписчики', 'tag' => 'subscribers', 'class' => 'admin/distribution/SubscribersPage.php', 'template' => 'admin/distribution/subscribers.tpl', 'children' => array(
                        0 => array(
                            'title' => 'Редактирование подписчика', 'tag' => 'subscriber_edit', 'mode' => 'hidden', 'class' => 'admin/distribution/SubscriberEditPage.php', 'template' => 'admin/distribution/subscriber_edit.tpl'
                        ),
                    ),
                ),
            ),
        ),
		
		15 => array(
			'title' => 'Заказы', 'tag' => 'orders', 'class' => 'admin/orders/OrdersPage.php', 'template' => 'admin/orders/orders.tpl', 'children' => array(
				0 => array(
					'title' => 'Редактирование заказа', 'tag' => 'order_edit', 'mode' => 'hidden', 'class' => 'admin/orders/OrderEditPage.php', 'template' => 'admin/orders/order_edit.tpl'
				),
			),
		),
	),
);
?>