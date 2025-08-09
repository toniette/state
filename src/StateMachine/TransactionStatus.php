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
    #[TransitionCollection(
        new Transition(self::SEND_TO_ANALYSIS, self::IN_ANALYSIS)
    )]
    case PENDING = 'pending';

    #[TransitionCollection(
        new Transition(self::APPROVE, self::APPROVED),
        new Transition(self::REJECT, self::REJECTED)
    )]
    case IN_ANALYSIS = 'in_analysis';

    #[TransitionCollection(
        new Transition(self::COMPLETE, self::COMPLETED),
        new Transition(self::FAIL, self::FAILED)
    )]
    case PROCESSING = 'processing';

    #[TransitionCollection(
        new Transition(self::PROCESS, self::PROCESSING)
    )]
    case APPROVED = 'approved';

    #[TransitionCollection(
        new Transition(self::CANCEL, self::CANCELED),
        new Transition(self::SEND_TO_ANALYSIS, self::IN_ANALYSIS)
    )]
    case REJECTED = 'rejected';

    #[TransitionCollection(
        new Transition(self::CANCEL, self::CANCELED),
        new Transition(self::SEND_TO_ANALYSIS, self::IN_ANALYSIS)
    )]
    case FAILED = 'failed';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    // Transition names
    private const string SEND_TO_ANALYSIS = 'sendToAnalysis';
    private const string APPROVE = 'approve';
    private const string REJECT = 'reject';
    private const string PROCESS = 'process';
    private const string COMPLETE = 'complete';
    private const string FAIL = 'fail';
    private const string CANCEL = 'cancel';
}