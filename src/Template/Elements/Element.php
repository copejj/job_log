<?php
namespace Jeff\Code\Template\Elements;

interface Element
{
	public function __toString(): string;
}