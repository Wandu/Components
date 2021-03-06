<?php
namespace Wandu\Database\Query;

use Wandu\Database\Contracts\QueryInterface;

/**
 * @see http://dev.mysql.com/doc/refman/5.7/en/truncate-table.html
 *
 * TRUNCATE TABLE tbl_name
 */
class TruncateQuery implements QueryInterface
{
    /** @var string */
    protected $table;

    /**
     * @param string $table
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * {@inheritdoc}
     */
    public function toSql()
    {
        return "TRUNCATE TABLE `{$this->table}`";
    }

    /**
     * {@inheritdoc}
     */
    public function getBindings()
    {
        return [];
    }
}
