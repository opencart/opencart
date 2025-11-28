export class Db {
    db = null;
    data = [];
    event  = {
        connect: () => {
            let indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB || window.shimIndexedDB;

            let open = indexedDB.open('opencart', 1);

            open.addEventListener('success', () => this.event.success);
            open.addEventListener('error', () => this.event.error);

            var tx = db.transaction('opencart', 'readwrite');

            var store = tx.objectStore('opencart');

            var index = store.index('NameIndex');


        },
        onconnect: () => {

        },
        success: () => {
            this.db = transaction.result;

            let transaction =  this.db.transaction('books', 'readwrite');


            console.log('Successfully opened DB');
        },
        error: () => {
            console.log('Error opening DB')
        }
    };

    constructor() {
        //this.event.connect();

    }

    addTable() {

    }

    deleteTable () {

    }

    get() {

    }
}