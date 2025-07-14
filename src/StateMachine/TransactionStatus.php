<?php

namespace Toniette\StateMachine;

/**
 * @method sendToAnalysis()
 * @method approve()
 * @method reject()
 * @method process()
 * @method complete()
 * @method fail()
 * @method cancel()
 */
enum TransactionStatus: string implements State
{
    // Transaction status
    case PENDING = 'pending';
    case IN_ANALYSIS = 'in_analysis';
    case PROCESSING = 'processing';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELED = 'canceled';

    // Transition names
    private const string SEND_TO_ANALYSIS = 'sendToAnalysis';
    private const string APPROVE = 'approve';
    private const string REJECT = 'reject';
    private const string PROCESS = 'process';
    private const string COMPLETE = 'complete';
    private const string FAIL = 'fail';
    private const string CANCEL = 'cancel';

    public function allowedTransitions(): TransitionCollection
    {
        return match ($this) {
            self::PENDING => TransitionCollection::from(
                new Transition(self::SEND_TO_ANALYSIS, self::IN_ANALYSIS)
            ),
            self::IN_ANALYSIS => TransitionCollection::from(
                new Transition(self::APPROVE, self::APPROVED),
                new Transition(self::REJECT, self::REJECTED)
            ),
            self::REJECTED, self::FAILED => TransitionCollection::from(
                new Transition(self::CANCEL, self::CANCELED),
                new Transition(self::SEND_TO_ANALYSIS, self::IN_ANALYSIS)
            ),
            self::APPROVED => TransitionCollection::from(
                new Transition(self::PROCESS, self::PROCESSING)
            ),
            self::PROCESSING => TransitionCollection::from(
                new Transition(self::COMPLETE, self::COMPLETED),
                new Transition(self::FAIL, self::FAILED)
            ),
            default => TransitionCollection::from()
        };
    }
}