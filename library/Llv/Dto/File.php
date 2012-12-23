<?php
/**
 * PHP Class File.php
 * PHP Version 5
 *
 * @category    : default
 * @package     : default
 * @license     : Not for free use
 * @date        : 28/07/12
 * @author      : aroy <contact@aroy.fr>
 */

class Llv_Dto_File
{
    /** @var string */
    public $filename;
    /** @var string */
    public $originalFilename;
    /** @var string */
    public $mimeType;
    /** @var string */
    public $extension;
    /** @var string */
    public $tmpName;
    /** @var int */
    public $error;
    /** @var int */
    public $size;

    /** @var int */
    public $id;
    /** @var bool */
    public $online;
    /** @var int */
    public $position;
    /** @var DateTime */
    public $dateAdd;
    /** @var DateTime */
    public $dateDelete;
}