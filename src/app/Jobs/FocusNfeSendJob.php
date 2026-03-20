<?php

namespace Sysborg\FocusNfe\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job abstrato base para envio de documentos fiscais via fila (queue).
 *
 * Estenda esta classe para criar jobs de envio em massa de NFe, NFSe, CTe, etc.
 *
 * Exemplo de uso:
 * ```php
 * class EnviarNFeJob extends FocusNfeSendJob
 * {
 *     public function __construct(private NFeDTO $data, private string $referencia) {}
 *
 *     public function handle(NFe $service): void
 *     {
 *         $service->envia($this->data, $this->referencia);
 *     }
 * }
 *
 * // Despachando:
 * EnviarNFeJob::dispatch($nfeData, 'REF001');
 *
 * // Despachando em lote:
 * Bus::batch(array_map(
 *     fn($item) => new EnviarNFeJob($item['dto'], $item['ref']),
 *     $lote
 * ))->dispatch();
 * ```
 */
abstract class FocusNfeSendJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Número máximo de tentativas antes de marcar o job como falhado
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * Timeout máximo em segundos para execução do job
     *
     * @var int
     */
    public int $timeout = 60;

    /**
     * Calcula o delay em segundos para cada tentativa (exponential backoff)
     *
     * @return array<int>
     */
    public function backoff(): array
    {
        return [1, 5, 10];
    }

    /**
     * Lógica de envio a ser implementada na classe filha
     *
     * @return void
     */
    abstract public function handle(): void;
}
