<?php

namespace App\Filament\Resources\PaymentMethods\Pages;

use App\Filament\Resources\PaymentMethods\PaymentMethodResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPaymentMethod extends EditRecord
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function (DeleteAction $action) {
                    $record = $this->getRecord();

                    if ($record->accounts()->exists()) {
                        Notification::make()
                            ->title('Não é possível excluir')
                            ->body('Este metodo de pagamento está vinculado a contas e não pode ser excluído.')
                            ->danger()
                            ->persistent()
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
