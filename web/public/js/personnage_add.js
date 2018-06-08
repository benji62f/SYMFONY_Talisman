/** JavaScript File
 **/

function reroll()
{
    $('#personnage_life').val(Math.floor(Math.random() * (100 + 1) + 1));
    $('#personnage_strengh').val(Math.floor(Math.random() * (100 + 1) + 1));
    $('#personnage_armor').val(Math.floor(Math.random() * (100 + 1) + 1));
    $('#personnage_dexterity').val(Math.floor(Math.random() * (100 + 1) + 1));
}
$('#personnage_reroll').click(function(){
    if(! $('#personnage_name').val()) { $('#personnage_name').val("noname");}
    reroll();
});
$(document).ready(function(){
    reroll();
});
