<? if ($article_found): ?>
	<section id="main">
        <div itemscope itemtype="http://schema.org/WebPage">
        <ul class="bread_crumbs" itemprop="breadcrumb">
            <li><a href="<? echo $HTTP; ?>">Главная</a></li>
			<? if (!$article_uri): ?><li>Статьи</li><? else: ?><li><a href="<? echo $HTTP; ?>articles.html">Статьи</a></li><li><? echo $article_arr[1]['title']; ?></li><? endif; ?>
        </ul>
        </div>
        <div class="row">
            <div class="twelve columns">
                <? if (!$article_uri): ?><h1 class="heading">Статьи</h1><? endif; ?>
                <div class="white_block">

				<? if (!$article_uri): ?>
					<? foreach($article_arr as $item): ?>
					<div class="article_item">
						<div class="top_article">
							<span class="price"><? echo $item['title']; ?></span>
						</div>
						<div class="article_date">
							<span class="public_date"><strong><? echo $item['public_date']; ?></strong></span>
						</div>
						<?
							if (!empty($item['image_filename']))
							echo '<img class="news_img" src="'.$HTTP.'pub/articles/'.$item['id'].'/'.$item['image_filename'].'" alt="'.$item['title'].'" />';
						?>

						<? $pos = strpos($item['description'], '<p><!-- more --></p>'); if ($pos != false) echo substr($item['description'], 0, $pos); else  echo $item['description']; ?>


                    <div class="article_more"><a href="<? echo $HTTP.'articles/'.$item['uri'].'.html'; ?>">Подробнее</a></div>
				</div>
				<? endforeach; ?>

				<? else: ?>

						<div class="top_article">
							<span class="price"><? echo $article_arr[1]['title']; ?></span>
						</div>
						<div class="article_date">
							<span class="public_date"><strong><? echo $article_arr[1]['public_date']; ?></strong></span>
						</div>
						<?
						if (!empty($article_arr[1]['image_filename']))
							echo '<img class="news_img" src="'.$HTTP.'pub/articles/'.$article_arr[1]['id'].'/'.$article_arr[1]['image_filename'].'" alt="'.$article_arr[1]['title'].'" />';
						?>
						<? echo $article_arr[1]['description']; ?>
                    </div>
				</div>

                    <div class="row">
                        <div class="twelve columns">
                            <span class="heading">Комментарии</span>

                            <div class="reviews_message" id="reviews_message">
								<? if ($comment_found): ?>
                                <span class="arrow"></span>
								<? foreach($comment_arr as $item): ?>
                                    <div class="message_item">
                                        <p><? echo $item['description']; ?></p>
                                        <span class="author"><? echo $item['name']; ?></span>
                                    </div>
									<? endforeach; ?>
								<? endif; ?>
                            </div><!-- review -->

                            <!-- -->
                            <div class="white_block">
								<div id="scroll-add-review">&nbsp;</div>
								<p class="t-center" id="box-btn">
                                <span class="button medium add_review" id="add_review_button">Поделись своим мнением</span>
								</p>

                                <div class="review_form" id="review_form">
								<? if(($_errors && !empty($_errors)) || ($comment_added == true)): ?>
									<script type="text/javascript">showAddReview = true;</script>
								<? endif; ?>
                                    <span class="heading_two">Написать комментарий</span>
									<? CForm::begin('add_comment', 'post', '', '', 'f-form'); ?>
									<? if ($comment_added): ?><span class="arrow"></span>
										<div align="center" style="margin: 20px auto;">Спасибо за ваше сообщение, оно будет опубликовано на сайте после прохождения модерации!</div>
									<? endif; ?>
                                    <div class="row">
                                        <div class="two columns">
                                            <label for="name">Ваше имя</label>
                                        </div>
                                        <div class="ten columns">
											<? CTemplate::input('text', 'name', 'name', 'input_style'); ?>
                                            <? if (strlen($error_name)): ?><span class="error">Введите имя!</span><? endif; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <script type="text/javascript">var select = "rate";</script>
                                    </div>
                                    <div class="row">
                                        <div class="two columns">
                                            <label for="message">Комментарий</label>
                                        </div>
                                        <div class="ten columns">
											<? CTemplate::textarea('description', 'description', 'textarea_style'); ?>
                                            <? if (strlen($error_text)): ?><span class="error">Введите текст комментария!</span><? endif; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="two columns"></div>
                                        <div class="ten columns">
                                            <a href="javascript: $('#add_comment').submit();" class="button medium send">Отправить</a>
                                        </div>
                                    </div>
									<? CForm::end(); ?>
                                </div><!-- review_form -->
                            </div>
                            <!-- -->


                        </div>
                    </div>

				<? endif; ?>

                </div>

			

            <? CControl::process('Pager', 'Articles'); ?>
            </div>
	</div>

<? endif; ?>

</section>