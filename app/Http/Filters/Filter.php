<?php 

namespace App\Http\Filters;

use DeepCopy\Exception\PropertyException;
use Illuminate\Http\Request;

class Filter 
{
    protected array $allowedOperatorsFields = [];

    protected array $translateOperatorsFields = [
        'gt' => '>',
        'lt' => '<',
        'eq' => '=',
        'gte' => '>=',
        'lte' => '<=',
        'ne' => '!=',
        'in' => 'in',
    ];


    public function filter(Request $request)
    {
        $where = [];
        $whereIn = [];

        if (empty($this->allowedOperatorsFields))
        {
            throw new PropertyException("Propriedade allowedOperatorsFields vazia");
        }

        foreach($this->allowedOperatorsFields as $param => $operators) {
            $queryOperator = $request->query($param);
            
            if($queryOperator) {
                var_dump($queryOperator);
                foreach($queryOperator as $operator => $value) {
                    if(!in_array($operator, $operators)) {
                        throw new PropertyException("Operador não permitido para o campo $param");
                    }

                    if(str_contains($value, '[')) {
                        $whereIn[] = [
                            $param,
                            explode(',', str_replace(['[', ']'], ['', ''], $value)),
                            $value
                        ];
                    } else {
                        $where[] = [
                            $param,
                            $this->translateOperatorsFields[$operator],
                            $value
                        ];
                    }
                }
            }
        }

        if(empty($where) && empty($whereIn)) {
            return [];
        }

        return [
            'where' => $where,
            'whereIn' => $whereIn
        ];
    }
}

?>