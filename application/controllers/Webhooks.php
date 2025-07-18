<?php

class Webhooks extends MW_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('User_model');
        $this->load->model('User_payment_option_model');
        $this->load->library('stripe');
    }

    /**
     * Entry point for Strip webhooks
     */
    public function stripeWebhook()
    {
        $postdata = @file_get_contents("php://input");
        $event = json_decode($postdata);

        switch ($event->type) {
            case 'customer.source.deleted':
                $this->deleteCardWebhook($event);
                break;
            case 'customer.bank_account.deleted':
                $this->deleteBankAccountWebhook($event);
                break;
            case 'customer.deleted':
                $this->deleteCustomerWebhook($event);
                break;
            default:
        }
    }

    /**
     * Stripe Delete Payment Method Event
     *
     * @param $event
     */
    protected function deleteCardWebhook($event)
    {
        $this->User_payment_option_model->delete_by(['token' => $event->data->object->id]);
    }

    /**
     * Stripe Delete Bank Account Event
     *
     * @param $event
     */
    protected function deleteBankAccountWebhook($event)
    {
        $this->User_payment_option_model->delete_by(['token' => $event->data->object->id]);
    }

    /**
     * Stripe Delete Customer Event
     *
     * @param $event
     */
    protected function deleteCustomerWebhook($event)
    {
        $user = $this->User_model->get_by(['stripe_id' => $event->data->object->id]);
        if ($user) {
            $this->User_payment_option_model->delete_by(['user_id' => $user->id]);
        }
    }
}