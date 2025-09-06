<?php

namespace App\Filament\Resources\Units\Pages;

use App\Filament\Resources\Units\UnitResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUnit extends EditRecord
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function (DeleteAction $action) {
                    $record = $this->getRecord();

                    if ($record->accounts()->exists()) {
                        Notification::make()
                            ->title('Não é possível excluir')
                            ->body('Esta unidade está vinculada a contas e não pode ser excluída.')
                            ->danger()
                            ->persistent()
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
