<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use Test\Setup;
use Braintree;

class DisputeTest extends Setup
{
    private $attributes;

    public function __construct() {
        $this->attributes = [
            'amount' => '100.00',
            'amountDisputed' => '100.00',
            'amountWon' => '0.00',
            'caseNumber' => 'CB123456',
            'createdAt' => DateTime::createFromFormat('Ymd-His', '20130410-105039'),
            'currencyIsoCode' => 'USD',
            'dateOpened' => DateTime::createFromFormat('Ymd-His', '20130401-000000'),
            'dateWon' => DateTime::createFromFormat('Ymd-His', '20130402-000000'),
            'forwardedComments' => 'Forwarded comments',
            'id' => '123456',
            'kind' => 'chargeback',
            'merchantAccountId' => 'abc123',
            'originalDisputeId' => 'original_dispute_id',
            'reason' => 'fraud',
            'reasonCode' => '83',
            'reasonDescription' => 'Reason code 83 description',
            'receivedDate' => DateTime::createFromFormat('Ymd-His', '20130410-000410'),
            'referenceNumber' => '123456',
            'replyByDate' => DateTime::createFromFormat('Ymd-His', '20130417-0000417'),
            'status' => 'open',
            'updatedAt' => DateTime::createFromFormat('Ymd-His', '20130410-105039'),
            'evidence' => [[
                'category' => NULL,
                'comment' => NULL,
                'createdAt' => DateTime::createFromFormat('Ymd-His', '20130411-105039'),
                'id' => 'evidence1',
                'sentToProcessorAt' => NULL,
                'sequenceNumber' => NULL,
                'url' => 'url_of_file_evidence',
            ],[
                'comment' => 'text evidence',
                'createdAt' => DateTime::createFromFormat('Ymd-His', '20130411-105039'),
                'id' => 'evidence2',
                'sentToProcessorAt' => '2009-04-11',
                'url' => NULL,
            ]],
            'statusHistory' => [[
                'effectiveDate' => '2013-04-10',
                'status' => 'open',
                'timestamp' => DateTime::createFromFormat('Ymd-His', '20130410-105039'),
            ]],
            'transaction' => [
                'id' => 'transaction_id',
                'amount' => '100.00',
                'createdAt' => DateTime::createFromFormat('Ymd-His', '20130319-105039'),
                'orderId' => NULL,
                'purchaseOrderNumber' => 'po',
                'paymentInstrumentSubtype' => 'Visa',
            ]
        ];
    }

    public function testLegacyConstructor()
    {
        $legacyParams = [
            'transaction' => [
                'id' => 'transaction_id',
                'amount' => '100.00',
            ],
            'id' => '123456',
            'currencyIsoCode' => 'USD',
            'status' => 'open',
            'amount' => '100.00',
            'receivedDate' => DateTime::createFromFormat('Ymd-His', '20130410-000410'),
            'replyByDate' => DateTime::createFromFormat('Ymd-His', '20130421-000421'),
            'reason' => 'fraud',
            'transactionIds' => [
                'asdf', 'qwer'
            ],
            'dateOpened' => DateTime::createFromFormat('Ymd-His', '20130410-000410'),
            'dateWon' =>DateTime::createFromFormat('Ymd-His', '20130422-000422'),
            'kind' => 'chargeback'
        ];

        $dispute = Braintree\Dispute::factory($legacyParams);

        $this->assertEquals('123456', $dispute->id);
        $this->assertEquals('100.00', $dispute->amount);
        $this->assertEquals('USD', $dispute->currencyIsoCode);
        $this->assertEquals(Braintree\Dispute::FRAUD, $dispute->reason);
        $this->assertEquals(Braintree\Dispute::OPEN, $dispute->status);
        $this->assertEquals(Braintree\Dispute::Open, $dispute->status);
        $this->assertEquals('transaction_id', $dispute->transactionDetails->id);
        $this->assertEquals('100.00', $dispute->transactionDetails->amount);
        $this->assertEquals(DateTime::createFromFormat('Ymd-His', '20130410-000410'), $dispute->dateOpened);
        $this->assertEquals(DateTime::createFromFormat('Ymd-His', '20130422-000422'), $dispute->dateWon);
        $this->assertEquals(Braintree\Dispute::CHARGEBACK, $dispute->kind);
    }

    public function testLegacyParamsWithNewAttributes()
    {
        $dispute = Braintree\Dispute::factory($this->attributes);

        $this->assertEquals('123456', $dispute->id);
        $this->assertEquals('100.00', $dispute->amount);
        $this->assertEquals('USD', $dispute->currencyIsoCode);
        $this->assertEquals(Braintree\Dispute::FRAUD, $dispute->reason);
        $this->assertEquals(Braintree\Dispute::Open, $dispute->status);
        $this->assertEquals(Braintree\Dispute::OPEN, $dispute->status);
        $this->assertEquals('transaction_id', $dispute->transactionDetails->id);
        $this->assertEquals('100.00', $dispute->transactionDetails->amount);
        $this->assertEquals(DateTime::createFromFormat('Ymd-His', '20130401-000000'), $dispute->dateOpened);
        $this->assertEquals(DateTime::createFromFormat('Ymd-His', '20130402-000000'), $dispute->dateWon);
        $this->assertEquals(Braintree\Dispute::CHARGEBACK, $dispute->kind);
    }

    public function testConstructorPopulatesNewFields()
    {
        $dispute = Braintree\Dispute::factory($this->attributes);

        $this->assertEquals("100.00", $dispute->amountDisputed);
        $this->assertEquals("0.00", $dispute->amountWon);
        $this->assertEquals("CB123456", $dispute->caseNumber);
        $this->assertEquals(DateTime::createFromFormat('Ymd-His', '20130410-105039'), $dispute->createdAt);
        $this->assertEquals("Forwarded comments", $dispute->forwardedComments);
        $this->assertEquals("abc123", $dispute->merchantAccountId);
        $this->assertEquals("original_dispute_id", $dispute->originalDisputeId);
        $this->assertEquals("83", $dispute->reasonCode);
        $this->assertEquals("Reason code 83 description", $dispute->reasonDescription);
        $this->assertEquals("123456", $dispute->referenceNumber);
        $this->assertEquals(DateTime::createFromFormat('Ymd-His', '20130410-105039'), $dispute->updatedAt);
        $this->assertNull($dispute->evidence[0]->comment);
        $this->assertEquals(DateTime::createFromFormat('Ymd-His', '20130411-105039'), $dispute->evidence[0]->createdAt);
        $this->assertNull($dispute->evidence[0]->category);
        $this->assertEquals('evidence1', $dispute->evidence[0]->id);
        $this->assertNull($dispute->evidence[0]->sentToProcessorAt);
        $this->assertNull($dispute->evidence[0]->sequenceNumber);
        $this->assertEquals('url_of_file_evidence', $dispute->evidence[0]->url);
        $this->assertEquals('text evidence', $dispute->evidence[1]->comment);
        $this->assertEquals(DateTime::createFromFormat('Ymd-His', '20130411-105039'), $dispute->evidence[1]->createdAt);
        $this->assertEquals('evidence2', $dispute->evidence[1]->id);
        $this->assertEquals('2009-04-11', $dispute->evidence[1]->sentToProcessorAt);
        $this->assertNull($dispute->evidence[1]->url);
        $this->assertEquals('2013-04-10', $dispute->statusHistory[0]->effectiveDate);
        $this->assertEquals('open', $dispute->statusHistory[0]->status);
        $this->assertEquals(DateTime::createFromFormat('Ymd-His', '20130410-105039'), $dispute->statusHistory[0]->timestamp);
    }

    public function testConstructorHandlesNullFields()
    {
        $emptyAttributes = [
            'amount' => NULL,
            'dateOpened' => NULL,
            'dateWon' => NULL,
            'evidence' => NULL,
            'replyByDate' => NULL,
            'statusHistory' => NULL
        ];

        $attrs = array_merge([], $this->attributes, $emptyAttributes);

        $dispute = Braintree\Dispute::factory($attrs);

        $this->assertNull($dispute->amount);
        $this->assertNull($dispute->dateOpened);
        $this->assertNull($dispute->dateWon);
        $this->assertNull($dispute->evidence);
        $this->assertNull($dispute->replyByDate);
        $this->assertNull($dispute->statusHistory);
    }

    public function testConstructorPopulatesTransaction()
    {
        $dispute = Braintree\Dispute::factory($this->attributes);

        $this->assertEquals('transaction_id', $dispute->transaction->id);
        $this->assertEquals('100.00', $dispute->transaction->amount);
        $this->assertEquals(DateTime::createFromFormat('Ymd-His', '20130319-105039'), $dispute->transaction->createdAt);
        $this->assertNull($dispute->transaction->orderId);
        $this->assertEquals('po', $dispute->transaction->purchaseOrderNumber);
        $this->assertEquals('Visa', $dispute->transaction->paymentInstrumentSubtype);
    }

    public function testAcceptNullRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'dispute with id "" not found');

        Braintree\Dispute::accept(null);
    }

	public function testAcceptEmptyIdRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'dispute with id " " not found');

        Braintree\Dispute::accept(" ");
    }

	public function testAddTextEvidenceEmptyIdRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'dispute with id " " not found');

        Braintree\Dispute::addTextEvidence(" ", "evidence");
    }

	public function testAddTextEvidenceNullIdRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'dispute with id "" not found');

        Braintree\Dispute::addTextEvidence(null, "evidence");
    }

	public function testAddTextEvidenceEmptyEvidenceRaisesValueException()
    {
        $this->setExpectedException('InvalidArgumentException', 'content cannot be blank');

        Braintree\Dispute::addTextEvidence("disputeId", " ");
    }

	public function testAddTextEvidenceNullEvidenceRaisesValueException()
    {
        $this->setExpectedException('InvalidArgumentException', 'content cannot be blank');

        Braintree\Dispute::addTextEvidence("disputeId", null);
    }

	public function testAddTextEvidenceBlankRequestContentRaisesValueException()
    {
        $this->setExpectedException('InvalidArgumentException', 'content cannot be blank');

        Braintree\Dispute::addTextEvidence("disputeId",
            [
                'content' => ' ',
                'category' => 'CARRIER_NAME',
                'sequenceNumber' => '0',
            ]
        );
    }

	public function testAddTextEvidenceNullRequestContentRaisesValueException()
    {
        $this->setExpectedException('InvalidArgumentException', 'content cannot be blank');

        Braintree\Dispute::addTextEvidence("disputeId",
            [
                'content' => null,
                'category' => 'CARRIER_NAME',
                'sequenceNumber' => '0',
            ]
        );
    }

	public function testAddTextEvidenceBlankRequestCategoryRaisesValueException()
    {
        $this->setExpectedException('InvalidArgumentException', 'category cannot be blank');

        Braintree\Dispute::addTextEvidence("disputeId",
            [
                'content' => 'UPS',
                'category' => '',
                'sequenceNumber' => '0',
            ]
        );
    }

	public function testAddTextEvidenceBlankRequestSequenceNumberRaisesValueException()
    {
        $this->setExpectedException('InvalidArgumentException', 'sequenceNumber cannot be blank');

        Braintree\Dispute::addTextEvidence("disputeId",
            [
                'content' => 'UPS',
                'category' => 'CARRIER_NAME',
                'sequenceNumber' => '',
            ]
        );
    }

	public function testAddTextEvidenceNonIntegerNumberRequestSequenceNumberRaisesValueException()
    {
        $this->setExpectedException('InvalidArgumentException', 'sequenceNumber must be an int');

        Braintree\Dispute::addTextEvidence("disputeId",
            [
                'content' => 'UPS',
                'category' => 'CARRIER_NAME',
                'sequenceNumber' => '4.5',
            ]
        );
    }

	public function testAddTextEvidenceNonIntegerStringRequestSequenceNumberRaisesValueException()
    {
        $this->setExpectedException('InvalidArgumentException', 'sequenceNumber must be an int');

        Braintree\Dispute::addTextEvidence("disputeId",
            [
                'content' => 'UPS',
                'category' => 'CARRIER_NAME',
                'sequenceNumber' => 'Blah',
            ]
        );
    }

    public function testAddFileEvidenceEmptyIdRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'dispute with id " " not found');

        Braintree\Dispute::addFileEvidence(" ", 1);
    }

    public function testAddFileEvidenceNullIdRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'dispute with id "" not found');

        Braintree\Dispute::addFileEvidence(null, 1);
    }

    public function testAddFileEvidenceEmptyEvidenceRaisesValueException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'document with id " " not found');

        Braintree\Dispute::addFileEvidence("disputeId", " ");
    }

    public function testAddFileEvidenceNullEvidenceRaisesValueException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'document with id "" not found');

        Braintree\Dispute::addFileEvidence("disputeId", null);
    }

    public function testAddFileEvidenceBlankRequestContentRaisesValueException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'document with id " " not found');

        Braintree\Dispute::addFileEvidence("disputeId",
            [
                'documentId' => ' ',
                'category' => 'GENERAL',
            ]
        );
    }

    public function testAddFileEvidenceNullRequestContentRaisesValueException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'document with id "" not found');

        Braintree\Dispute::addFileEvidence("disputeId",
            [
                'documentId' => null,
                'category' => 'GENERAL',
            ]
        );
    }

    public function testAddFileEvidenceBlankRequestCategoryRaisesValueException()
    {
        $this->setExpectedException('InvalidArgumentException', 'category cannot be blank');

        Braintree\Dispute::addFileEvidence("disputeId",
            [
                'documentId' => '123',
                'category' => '',
            ]
        );
    }

	public function testFinalizeNullRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'dispute with id "" not found');

        Braintree\Dispute::finalize(null);
    }

	public function testFinalizeEmptyIdRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'dispute with id " " not found');

        Braintree\Dispute::finalize(" ");
    }

	public function testFindingNullRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'dispute with id "" not found');

        Braintree\Dispute::find(null);
    }

	public function testFindingEmptyIdRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'dispute with id " " not found');

        Braintree\Dispute::find(" ");
    }

	public function testRemoveEvidenceEmptyDisputeIdRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'evidence with id "evidence" for dispute with id " " not found');

        Braintree\Dispute::removeEvidence(" ", "evidence");
    }

	public function testRemoveEvidenceNullDisputeIdRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'evidence with id "evidence" for dispute with id "" not found');

        Braintree\Dispute::removeEvidence(null, "evidence");
    }

	public function testRemoveEvidenceEvidenceNullIdRaisesNotFoundException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'evidence with id "" for dispute with id "dispute_id" not found');

        Braintree\Dispute::removeEvidence("dispute_id", null);
    }

	public function testRemoveEvidenceEmptyEvidenceIdRaisesValueException()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'evidence with id " " for dispute with id "dispute_id" not found');

        Braintree\Dispute::removeEvidence("dispute_id", " ");
    }
}
