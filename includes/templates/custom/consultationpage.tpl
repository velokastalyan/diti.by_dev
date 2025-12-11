<section id="main" xmlns="http://www.w3.org/1999/html">
    <div itemscope itemtype="http://schema.org/WebPage">
    <ul class="bread_crumbs" itemprop="breadcrumb">
        <li>
            <a href="<? echo $HTTP; ?>">Главная</a>
        </li>
        <li>
            Консультация
        </li>
    </ul>
    </div>
    <div class="row">
        <div class="twelve columns">
            <h1 class="heading">Получить консультацию</h1>

            <div class="white_block">
                <div class="row">
                    <div class="twelve columns">

                        <div class="consultation">

                            <p class="title">Вы хотите получить консультацию о <span>“<? if ($product_found) echo $product_arr[1]['title'].' - '.$product_arr[1]['b_title']; else echo 'Продукт не найден'; ?>”</span>.</p>

                            <p>Пожалуйста, оставьте свои контактные данные и мы свяжемся с вами в ближайшее время.</p>

							<? CForm::begin('consultation', 'POST', '', 'form', 'multipart/form-data'); ?>
                                <div class="row">
                                    <div class="four columns">
                                        <label for="f_name">Имя</label>
                                    </div>
                                    <div class="eight columns">
                                        <input id="f_name" name="name" type="text" value="<? echo $r_name; ?>" <? if ($error_name) echo 'class="error"'; ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="four columns">
                                        <label for="f_phone">Телефон</label>
                                    </div>
                                    <div class="eight columns">
                                        <input id="f_phone" name="phone" type="text" value="<? echo $r_phone; ?>" <? if ($error_phone) echo 'class="error"'; ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="four columns">
                                        <label for="f_name">Комментарий</label>
                                    </div>
                                    <div class="eight columns">
                                        <textarea id="f_comment" name="comment" value="<? echo $r_comment; ?>" <? if ($error_name) echo 'class="error"'; ?>></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="eight columns offset-by-four">
                                        <a class="button medium" onclick="javascript: submit();">Отправить</a>
                                    </div>
                                </div>
							<? CForm::end(); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>