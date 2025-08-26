<?php
namespace Jeff\Code\View\Elements;

use Jeff\Code\Model\Meta\Labels;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\Metadata;

class Table
{
	protected Attributes $attrs;
	protected Labels $labels;
	protected Metadata $metadata;

	protected array $data;

	public function __construct(Metadata $metadata, array $data, ?Attributes $attrs=null)
	{
		$this->metadata = $metadata;
		$this->data = $data;
		$this->attrs = new Attributes(['class' => 'table table-striped table-bordered']);
		if (!empty($attrs))
		{
			$this->attrs->merge($attrs);
		}
	}

	public function __toString(): string
	{
		$metadata = $this->metadata->get();
		$this->labels = new Labels(array_keys($metadata));

		$cells = [];
		foreach ($metadata as $key => $meta)
		{
			$class = '';
			if (!empty($meta['class']))
			{
				$class = " class='{$meta['class']}'";
			}
			$cells[] = "<th{$class}>{$this->labels->$key}</th>";
		}
		$headers = "<tr>" . implode($cells) . "</tr>";

		$rows = [];
		foreach ($this->data as $row)
		{
			$cells = [];
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

				$cells[] = "<td{$class}>{$text}</td>";
			}
			$rows[] = "<tr>" . implode($cells) . "</tr>";
		}
		$body = implode($rows);
		ob_start();
		$table_name = 'datatable_' . sprintf('%s', random_int(100000, 999999));
		?>
			<script>
				$(document).ready(function() {
					$('#<?=$table_name?>').DataTable({
						"paging": true,
						"searching": true,
						"info": true,
						"order": [[1, 'asc']],
						"columnDefs": [
						{
							"orderable": false,
							"targets": 0 // Disable sorting on the first column (index 0)
						}],
					});
				});
			</script>
			<div <?=$this->attrs?>>
				<table id='<?=$table_name?>'>
					<thead><?=$headers?></thead>
					<tbody><?=$body?></tbody>
				</table>
			</div>
		<?php

		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}
}
