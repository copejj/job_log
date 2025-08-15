<?php
namespace Jeff\Code\Template\Display;

interface Printable
{
	public function __toString(): string;
}