<?php


namespace App\Services;

use App\Http\UrlHelper;
use App\Http\UrlManager;
use App\Models\User;
use App\Repositories\TransactionRepository;

class TransactionService
{
    const UNABLE_COMPLETE_TRANSACTION = "We were unable to complete your transaction";
    const TRANSACTION_FULFILLED = "Your transaction was successful";
    const UNAUTHORIZED_BY_EXTERNAL_SERVICE = 'you were not authorized to make the transaction';

    const CODE_UNABLE_COMPLETE_TRANSACTION = 'CODE_UNABLE_COMPLETE_TRANSACTION';
    const CODE_TRANSACTION_FULFILLED = 'CODE_TRANSACTION_FULFILLED';
    const CODE_UNAUTHORIZED_EXTERNAL_SERVICE = 'CODE_UNAUTHORIZED_EXTERNAL_SERVICE';

    private $payer;
    private $payee;
    private $valueTransaction;
    private $transactionRepository;
    private $urlManager;
    public $initBalances = array();

    public function __construct(User $payer, User $payee, $value)
    {
        $this->payer = $payer;
        $this->payee = $payee;
        $this->initBalances = ['payer' => $payer->wallet->balance, 'payee' => $payee->wallet->balance];
        $this->valueTransaction = $value;
        $this->transactionRepository = new TransactionRepository();
        $this->urlManager = new UrlManager();

    }

    /**
     * This function is intended to make the call to an external authorization service
     * @return bool
     */
    public function getHasAutorization()
    {
        $response = $this->urlManager->get(UrlHelper::$externalAuthorizingUrl);
        return $response['success'] && $response['message'] == UrlHelper::AUTHORIZED_RESPONSE_MESSAGE;
    }

    /**
     * This function is intended to make the call to a third party service to send email
     * notification to the user
     * @return bool
     */
    public function sendNotificationReceivement()
    {
        $response = $this->urlManager->post(UrlHelper::$externalServiceEmailUrl, [
            'email' => $this->payee->email,
            'value' => $this->valueTransaction
        ]);
        return $response['success'] && $response['message'] == UrlHelper::SENT_EMAIL_RESPONSE_MESSAGE;
    }

    /**
     * This function is intended to make the call to the
     * repository layer to persist transaction data
     */
    public function recordsTransaction()
    {
        $this->transactionRepository->recordsTransaction($this->payer, $this->payee, $this->valueTransaction);
    }

    /**
     * This function aims to return the new balance of the
     * paying user based on the transaction in which it is being carried out.
     * @return mixed
     */
    public function getNewBalancePayer()
    {
        return $this->payer->wallet->balance - $this->valueTransaction;
    }

    /**
     * This function aims to return the new balance of the
     * receiving user based on the transaction in which it is being carried out.
     * @return mixed
     */
    public function getNewBalancePayee()
    {
        return $this->payee->wallet->balance + $this->valueTransaction;
    }
}
