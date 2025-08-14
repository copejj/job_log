<?php
namespace Jeff\Code\Template\Display;

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
				$text = $row->$key;
				if (!empty($meta['function']))
				{
					$text = $meta['format']($row);
				}
				else if (!empty($meta['format']))
				{
					$text = $meta['format']($text);
				}

				$cells[] = "<td>{$text}</td>";
			}
			$rows[] = "<tr>" . implode($cells) . "</tr>";
		}
		$body = implode($rows);
		return 
			"<div class='table_cont'>
				<table>
					<thead>{$headers}<thead>
					<tbody>{$body}</tbody>
				</table>
			</div>";
	}
}
