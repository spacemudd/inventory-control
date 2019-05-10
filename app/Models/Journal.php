<?php

namespace App\Models;

use Scottlaurent\Accounting\Models\Ledger;
use DB;

class Journal extends \Scottlaurent\Accounting\Models\Journal
{
    /**
     * @param Ledger $ledger
     * @return Journal
     */
    public function assignToLedger(Ledger $ledger)
    {
        $ledger->journals()->save($this);

        return $this;
    }

     /**
	 *
	 */
	public function resetCurrentBalances()
	{
		$this->balance = $this->getBalance();

		/**
		 * @see https://stackoverflow.com/questions/38650500/mssql-driver-issue-eloquent-save-tries-to-set-identity-column-on-update
		 */
		if (config('database.default') === 'sqlsrv') {
			unset($this->id);
		}

		$this->save();
	}
	
}
