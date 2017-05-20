update ItemCriterioAvaliacao set 
 formulaCalculo='( FALTAS > LIMITE_FALTAS ? "0.0" : ( MÉDIA === null && AVF === null ? "" : ( MÉDIA >= 7 ? MÉDIA : ( AVF === null ? null : ( MÉDIA + AVF ) / 2 ) ) ) )'
where formulaCalculo='( FALTAS > LIMITE_FALTAS ? "0.0" : ( MÉDIA === null && AVF === null ? "" : ( MÉDIA >= 7 ? MÉDIA : AVF === null ? null : ( MÉDIA + AVF ) / 2 ) ) )'
and idItemCriterioAvaliacao=5;