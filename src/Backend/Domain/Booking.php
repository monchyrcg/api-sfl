<?php
declare(strict_types=1);


namespace Sfl\Backend\Domain;


use Exception;
use Sfl\Backend\Domain\ValueObjects\BookingDate;
use Sfl\Backend\Domain\ValueObjects\BookingMargin;
use Sfl\Backend\Domain\ValueObjects\BookingNight;
use Sfl\Backend\Domain\ValueObjects\BookingRequestId;
use Sfl\Backend\Domain\ValueObjects\BookingSellingRate;
use Sfl\Shared\Domain\AggregateRoot;

class Booking implements AggregateRoot
{
    private float $profit;

    public function __construct(
        private BookingRequestId $bookingRequestId,
        private BookingDate $bookingDate,
        private BookingNight $bookingNight,
        private BookingSellingRate $bookingSellingRate,
        private BookingMargin $bookingMargin
    )
    {
    }

    /**
     * @throws Exception
     */
    public static function fromPrimitives(
        string $requestId, string $date, int $night, int $sellingRate, int $margin
    ): Booking
    {
        return new self(
            BookingRequestId::from($requestId),
            new BookingDate($date),
            BookingNight::from($night),
            BookingSellingRate::from($sellingRate),
            BookingMargin::from($margin)
        );
    }

    public function requestId():BookingRequestId
    {
        return $this->bookingRequestId;
    }

    public function date(): BookingDate
    {
        return $this->bookingDate;
    }

    public function night():BookingNight
    {
        return $this->bookingNight;
    }

    public function sellingRate(): BookingSellingRate
    {
        return $this->bookingSellingRate;
    }

    public function margin(): BookingMargin
    {
        return $this->bookingMargin;
    }

    public function profit(): float
    {
        return $this->profit;
    }

    public function calculateProfit()
    {
        //(selling_rate * margin (percentage)) / nights
        $this->profit = ($this->sellingRate()->value()*$this->margin()->value())/($this->night()->value()*100);
    }

    public function pullEvents(): iterable
    {
        // TODO: Implement pullEvents() method.
    }
}