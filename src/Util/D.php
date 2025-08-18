<?php
namespace Jeff\Code\Util;

class D
{
	private static $debug_count;

	static public function p($title, $data='', $backtrace='')
	{
		echo static::r($title, $data, $backtrace);
	}

	static public function r($title, $data='', $backtrace='')
	{
		$helper_html = '';
		if (self::$debug_count == 0)
		{
			$helper_html = self::getHelperHtml();
		}
		if (!$backtrace)
		{
			$backtrace = self::backtrace();
		}
		self::$debug_count++;
		$count = self::$debug_count;
		$rand = rand(100000, 999999);
		$expander = '';
		$exportdata = '';
		if ($data)
		{
			$expander = '+';
			$exportdata = "<div class='debug_body debug_{$rand}_{$count}' style='display:none'><pre>".print_r($data, true)."</pre></div>";
		}
		$titledata = "<div class='debug_head' onclick='show_debug({$count}, {$rand})'>".date("Y-m-d H:i:s")." <span class='debug_title'><i> {$title} </i> {$expander} </span> </div>";
		return "{$helper_html}<div class='debug_func' title='{$backtrace}' >{$titledata}{$exportdata}</div>";
	}

	static public function backtrace($sep="\n")
	{
		$backtrace = []; 
		foreach (debug_backtrace() as $line)
		{
			if ($line['function'] == __FUNCTION__)
				continue;
			$docroot = preg_quote($_SERVER['DOCUMENT_ROOT'] ?? '');
			$file = preg_replace("@{$docroot}/@", '', $line['file']);
			$function = $line['function'];
			$linenum = $line['line'];
			$backtrace[] = "{$file}:{$linenum} > {$function}()";
		}
		$back = implode($sep, $backtrace);
		return $back;
	}

	static private function getHelperHtml()
	{
		ob_start();
		?>
			<style type="text/css">
				.debug_head {
					font-weight: bold;
				}
				.debug_title {
					color: blue;
				}
				.debug_body {
					background-color: #f0f8ff75
				}
				.debug_func {
					background-color: #00800026;
					font-size: 12px;
				}
			</style>
			<script type="text/javascript">
				function show_debug(index, rand)
				{
					toggle(document.querySelectorAll('.debug_'+rand+'_'+index));
				}
				function toggle(elements, specifiedDisplay)
				{
					var element, index;

					elements = elements.length ? elements : [elements];
					for (index = 0; index < elements.length; index++)
					{
						element = elements[index];

						if (isElementHidden(element))
						{
							element.style.display = '';

							// If the element is still hidden after removing the inline display
							if (isElementHidden(element))
							{
								element.style.display = specifiedDisplay || 'block';
							}
						}
						else
						{
							element.style.display = 'none';
						}
					}

					function isElementHidden (element)
					{
						return window.getComputedStyle(element, null).getPropertyValue('display') === 'none';
					}
				}
			</script>
		<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
}
