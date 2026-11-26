<?php

namespace App\Services;

// Model que representa os períodos letivos.
use App\Models\Semestre;

// Classe responsável por gerar períodos letivos.
class GeradorPeriodosLetivos
{
    /**
     * GERAÇÃO AUTOMÁTICA DE PERÍODOS LETIVOS
     *
     * Exemplo:
     * Início: 2026.2
     * Fim: 2028.1
     *
     * Resultado:
     * 2026.2
     * 2027.1
     * 2027.2
     * 2028.1
     *
     * Períodos existentes não serão duplicados.
     */
    public function gerar(string $inicio, string $fim): int
    {
        // Verifica se os identificadores possuem
        // o formato AAAA.1 ou AAAA.2.
        if (
            !preg_match('/^\d{4}\.[12]$/', $inicio) ||
            !preg_match('/^\d{4}\.[12]$/', $fim)
        ) {
            throw new \InvalidArgumentException(
                'Informe períodos no formato AAAA.1 ou AAAA.2.'
            );
        }

        // Separa ano e semestre inicial.
        [$anoInicial, $semestreInicial] =
            array_map('intval', explode('.', $inicio));

        // Separa ano e semestre final.
        [$anoFinal, $semestreFinal] =
            array_map('intval', explode('.', $fim));

        // Converte os períodos em números sequenciais.
        // Isso permite comparar os períodos corretamente.
        $numeroInicial = $anoInicial * 2 + $semestreInicial;
        $numeroFinal = $anoFinal * 2 + $semestreFinal;

        // Impede que o período final seja anterior ao inicial.
        if ($numeroFinal < $numeroInicial) {
            throw new \InvalidArgumentException(
                'O período final não pode ser anterior ao inicial.'
            );
        }

        // Limita a geração a 40 semestres.
        if (($numeroFinal - $numeroInicial) > 39) {
            throw new \InvalidArgumentException(
                'O intervalo máximo é de 40 semestres.'
            );
        }

        // Contador dos períodos novos.
        $criados = 0;

        // Percorre todos os períodos do intervalo.
        for (
            $numero = $numeroInicial;
            $numero <= $numeroFinal;
            $numero++
        ) {
            // Recupera o ano do período.
            $ano = intdiv($numero - 1, 2);

            // Identifica se é o semestre 1 ou 2.
            $semestre = (($numero - 1) % 2) + 1;

            // Monta o identificador.
            $identificador = $ano . '.' . $semestre;

            // Cria somente se ainda não existir.
            $registro = Semestre::firstOrCreate([
                'identificador' => $identificador,
            ]);

            // Conta apenas os registros novos.
            if ($registro->wasRecentlyCreated) {
                $criados++;
            }
        }

        // Retorna a quantidade de períodos criados.
        return $criados;
    }
}
