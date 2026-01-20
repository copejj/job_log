<?php
namespace Jeff\Code\View\Elements;

use Jeff\Code\Model\Meta\Labels;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\DataTableAttributes;
use Jeff\Code\View\Display\Metadata;

class Table
{
	protected Labels $labels;
	protected Metadata $metadata;

	protected Attributes $attrs;
	protected DataTableAttributes $dt_attrs;

	protected array $data;

	public function __construct(Metadata $metadata, array $data, ?Attributes $table_attrs=null, ?DataTableAttributes $datatable_attrs=null)
	{
		$this->metadata = $metadata;
		$this->data = $data;

		$this->attrs = new Attributes(['class' => 'table table-striped table-bordered']);
		if (!empty($table_attrs))
		{
			$this->attrs->merge($table_attrs);
		}

		$this->dt_attrs = new DataTableAttributes(
		[
			'paging' => 'true',
			'searching' => 'true',
			'info' => 'true',
			'order' => '[[1, "asc"]]',
			'lengthMenu' => '[[10, 25, 50, -1], [10, 25, 50, "All"]]',
			'pageLength' => '-1',
			'columnDefs' => "[{ 'orderable': false, 'targets': 0 }]",
		]);
		if (!empty($datatable_attrs))
		{
			$this->dt_attrs->merge($datatable_attrs);
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

				$data_sort = strtolower($row->$key ?? '');
				if (empty($meta['format']))
				{
					$text = $row->$key;
				}
				else
				{
					$text = $meta['format']::format($key, $row);
				}

				$cells[] = "<td{$class} data-sort='{$data_sort}'>{$text}</td>";
			}
			$rows[] = "<tr>" . implode($cells) . "</tr>";
		}
		$body = implode($rows);
		ob_start();
		$table_name = 'datatable_' . sprintf('%s', random_int(100000, 999999));
		?>
			<script>

				$(document).ready(function() {
					DataTable.datetime('DD/MM/YYYY');

					$('#<?=$table_name?>').DataTable({<?=$this->dt_attrs?>});
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
