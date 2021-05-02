<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Services\UserService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    private $userService;
    private $walletService;
    private $transactionService;
    private $response;

    private $payer;
    private $payee;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userService = new UserService();
        $this->response = $this->generateResponse();
    }

    public function transaction(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            "payer" => "required|integer|min:0",
            "payee" => "required|integer|min:0",
            "value" => "required|regex:/^\d+(\.\d{1,2})?$/|min:0"
        ]);

        if ($validator->fails()) {
            $this->generateResponse(false, TransactionService::UNABLE_COMPLETE_TRANSACTION, TransactionService::CODE_UNABLE_COMPLETE_TRANSACTION);
            return response()
                ->json($this->response, 400);
        }

        if (!$this->userService->hasValidUserTransaction($params['payer'], $params['payee'])) {
            $this->generateResponse(false, UserService::USERS_INVALID_TRANSFER, UserService::CODE_USERS_INVALID);
            return response()
                ->json($this->response, 400);
        }

        $this->payer = $this->userService->getUser($params['payer']);
        $this->payee = $this->userService->getUser($params['payee']);

        if (!$this->userService->payerCanTransfer($params['payer'])) {
            $this->generateResponse(false, UserService::USER_CANNOT_TRANSFER, UserService::CODE_CANNOT_TRANSFER);
            return response()
                ->json($this->response, 400);
        }

        $this->walletService = new WalletService();
        if (!$this->walletService->payerHasBalance($this->payer, $params['value'])) {
            $this->generateResponse(false, WalletService::WALLET_INVALID_BALANCE, WalletService::CODE_WALLET_INVALID_BALANCE);
            return response()
                ->json($this->response, 400);
        }

        $this->transactionService = new TransactionService($this->payer, $this->payee, $params['value']);
        if (!$this->transactionService->getHasAutorization()) {
            $this->generateResponse(false, TransactionService::UNAUTHORIZED_BY_EXTERNAL_SERVICE, TransactionService::CODE_UNAUTHORIZED_EXTERNAL_SERVICE);
            return response()
                ->json($this->response, 401);
        }


        if ($this->walletService->makeTransfer($this->payer, $this->payee, [
            "payer" => $this->transactionService->getNewBalancePayer(),
            "payee" => $this->transactionService->getNewBalancePayee()])) {

            $this->generateResponse(true, TransactionService::TRANSACTION_FULFILLED, TransactionService::CODE_TRANSACTION_FULFILLED);
            if (!$this->transactionService->sendNotificationReceivement()) {
                $this->walletService->makeTransfer($this->payer, $this->payee, [
                    'payer' => $this->transactionService->initBalances['payer'],
                    'payee' => $this->transactionService->initBalances['payee']
                    ]);
                $this->generateResponse(false, TransactionService::UNABLE_COMPLETE_TRANSACTION, TransactionService::CODE_UNABLE_COMPLETE_TRANSACTION);
                return response()
                    ->json($this->response, 503);
            }
            $this->transactionService->recordsTransaction();
        }
        return response()
            ->json($this->response, 200);
    }

    public function generateResponse($status = false, $message = '', $code = '')
    {
        $this->response = [
            'success' => $status,
            'message' => $message,
            'code' => $code,
        ];
    }
}
