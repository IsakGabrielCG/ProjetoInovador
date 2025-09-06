<?php

namespace App\Filament\Resources\AccountTypes\Pages;

use App\Filament\Resources\AccountTypes\AccountTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAccountType extends EditRecord
{
    protected static string $resource = AccountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function (DeleteAction $action) {
                    $record = $this->getRecord();

                    if ($record->accounts()->exists()) {
                        Notification::make()
                            ->title('Não é possível excluir')
                            ->body('Este tipo de conta está vinculado a contas e não pode ser excluído.')
                            ->danger()
                            ->persistent()
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
