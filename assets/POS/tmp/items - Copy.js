function search(nameKey, myArray){
    var foundResult=new Array();
    for (var i=0; i < myArray.length; i++) {
        // if (myArray[i].item_name === nameKey) {
        //     return myArray[i];
        // }
        if (myArray[i].item_name.toLowerCase().includes(nameKey.toLowerCase()) || myArray[i].item_code.toLowerCase().includes(nameKey.toLowerCase()) || myArray[i].category_name.toLowerCase().includes(nameKey.toLowerCase()) || myArray[i].veg_item.includes(nameKey) || myArray[i].beverage_item.toUpperCase().includes(nameKey) || myArray[i].bar_item.toUpperCase().includes(nameKey)) {
            foundResult.push(myArray[i]);
            
        }
    }
    return foundResult.sort( function(a, b) {
      return parseInt(b.sold_for)-parseInt(a.sold_for);
    });
    //this is comment. it could be used if we want to sort this collection of object by item_name or anything else
    // return foundResult.sort( predicateBy("item_name") );
    
}
function searchAddress(nameKey, myArray){
    var foundResult=new Array();
    for (var i=0; i < myArray.length; i++) {
        // if (myArray[i].item_name === nameKey) {
        //     return myArray[i];
        // }
        if (myArray[i].customer_id == nameKey) {
            foundResult.push(myArray[i]);
            
        }
    }
    return foundResult;
    
}
function search_by_menu_id(menu_id,myArray){
    var foundResult=new Array();
    for (var i=0; i < myArray.length; i++) {
        if (myArray[i].item_id.includes(menu_id)) {
            foundResult.push(myArray[i]);
            
        }
    }
    return foundResult.sort();
}

function predicateBy(prop){
   return function(a,b){
      if( a[prop] > b[prop]){
          return 1;
      }else if( a[prop] < b[prop] ){
          return -1;
      }
      return 0;
   }
}