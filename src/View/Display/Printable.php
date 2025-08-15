<?php
namespace Jeff\Code\View\Display;

interface Printable
{
	public function __toString(): string;
}