@foreach($itensRetirados as $itemRetirado)


<strong> Retirado por: </strong> {{$itemRetirado->funcionario}} 
<br> 
<strong>no dia: </strong>{{Tratamento::datetimeBr($itemRetirado->dataRetirada) ?? "Sem reg."}} 
<br> 
<strong>devolvida no dia: </strong>{{Tratamento::datetimeBr($itemRetirado->dataDevolucao) ?? 'Não devolveu ainda'}}
<br>
<hr>


@endforeach