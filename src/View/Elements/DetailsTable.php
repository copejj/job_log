<?php
namespace Jeff\Code\View\Elements;

use Jeff\Code\Model\Meta\Labels;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\Metadata;

class DetailsTable
{
	protected Attributes $attrs;
	protected Labels $labels;
	protected Metadata $metadata;

	protected array $data;

	public function __construct(Metadata $metadata, array $data, ?Attributes $attrs=null)
	{
		$this->metadata = $metadata;
		$this->data = $data;
		$this->attrs = new Attributes(['class' => 'table table-details']);
		if (!empty($attrs))
		{
			$this->attrs->merge($attrs);
		}
	}

	public function __toString(): string
	{
		$metadata = $this->metadata->get();
		$this->labels = new Labels($this->metadata->keys());

		$rows = [];
		foreach ($this->data as $row)
		{
			foreach ($metadata as $key => $meta)
			{
				$class = '';
				if (!empty($meta['class']))
				{
					$class = " class='{$meta['class']}'";
				}

				if (empty($meta['format']))
				{
					$text = $row->$key;
				}
				else
				{
					$text = $meta['format']::format($key, $row);
				}
				$input = new Input($key, 'text', $text, '', '', $this->attrs);
				$rows[] = "<tr{$class}><th>{$this->labels->$key}</th><td>{$input}</td></tr>";
			}
		}
		$body = implode($rows);

		ob_start();
		$table_name = 'detailtable_' . sprintf('%s', random_int(100000, 999999));
		?>
			<div <?=$this->attrs?>>
				<table id='<?=$table_name?>'>
					<tbody><?=$body?></tbody>
				</table>
			</div>
		<?php
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}
}