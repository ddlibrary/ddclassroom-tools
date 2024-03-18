function printDiv(id) {
    $('#'+id).printThis({
        debug: false,               //* show the iframe for debugging
        importCSS: true,            //* import page CSS
        importStyle: false,         //* import style tags
        printContainer: true,       //* grab outer container as well as the contents of the selector
        loadCSS: [""],  //* path to additional css file - us an array [] for multiple
        pageTitle: "",              //* add title to print page
        removeInline: false,        //* remove all inline styles from print elements
        printDelay: 333,            //* variable print delay; depending on complexity a higher value may be necessary
        header: null,               //* prefix to html
        // header: "<h1>Your header here</h1>",
        formValues: false
    });
}




