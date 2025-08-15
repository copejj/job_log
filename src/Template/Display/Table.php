<?php
namespace Jeff\Code\Template\Display;

use ReflectionClass;

class Table
{
	protected Metadata $metadata;
	protected array $data;

	public function __construct(Metadata $metadata, array $data)
	{
		$this->metadata = $metadata;
		$this->data = $data;
	}

	public function __toString(): string
	{
		$metadata = $this->metadata->get();
		$cells = [];
		foreach ($metadata as $meta)
		{
			$cells[] = "<th>" . ($meta['label'] ?? '') . "</th>";
		}
		$headers = "<tr>" . implode($cells) . "</tr>";

		$rows = [];
		foreach ($this->data as $row)
		{
			$cells = [];
			foreach ($metadata as $key => $meta)
			{
				if (empty($meta['format']))
				{
					$text = $row->$key;
				}
				else
				{
					$text = $meta['format']::format($key, $row);
				}

				$cells[] = "<td>{$text}</td>";
			}
			$rows[] = "<tr>" . implode($cells) . "</tr>";
		}
		$body = implode($rows);
		ob_start();
		?>
			<script>
				$(document).ready(function() {
					$('#datatable').DataTable({
						// Optional: Add DataTables options here
						// For example, to enable searching and pagination:
						"paging": true,
						"searching": true,
						"info": true
					});
				});
			</script>
			<div class='table_cont'>
				<table id='datatable' class='table table-striped table-bordered'>
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
