<html>
	<h1>{[Welcome]}</h1>
	<p>{[Context]}</p>
    
    @{
        foreach($items as $item):
    @}
            {{$item."<br>"}}
    @{
        endforeach;
    @}
	<p>
        {{"For Changing Language add one of these extensions to the end of url: en, az, ru. For Instance: mvc_git/az/, mvc_git/ru/, mvc_git/en/ "}}
    </p>
    <p>
        {{"To enable caching, call the function viewDocMem instead of viewDoc in controller"}}
    </p>
    
</html> 