<?php
ECPUser::logOut();
wp_redirect(get_bloginfo("url"));
die();