function lang() {
    var language = "fr";
    // if($('#in-enterprise-list').hasClass('en')) {
    //     language="en";
    // } else {
    //     language="fr";
    // }
    return language;
}

function debug(obj) {
    obj.map(function(e,x) {
        $.each(this.attributes, function() {
            // this.attributes is not a plain object, but an array
            // of attribute nodes, which contain both the name and value
            if(this.specified) {
                console.log('--- ', e, this.name, this.value);
            }
        });
    });
}

function translate(str) {
    if (typeof str !== 'undefined') {
        // the variable is defined
        return str.normalize('NFD').toLowerCase().replace(/ /g,"-").replace(/[\u0300-\u036f]/g, "");
    } else {
        return "";
    }
}