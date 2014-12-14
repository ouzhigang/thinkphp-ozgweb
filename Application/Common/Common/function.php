<?php
function page_count($count, $page_size) {
	if($count % $page_size == 0)
		return intval($count / $page_size);
	else
		return intval($count / $page_size) + 1;
}
