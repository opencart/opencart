import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('cms/topic');

export default class extends Controller {
    async connected() {

    }

    async render() {
        let data = {};

        //let topic = await loader.storage('cms/topic-' + this.getAttribute('topic_id'));

        //if (topic.length) {
         //   data.name = topic.name;
          //  data.description = topic.description;
          //  data.image = topic.image;

            //let articles = await loader.storage('cms/topic-' + this.getAttribute('topic_id'));

        //}

        return loader.template('cms/article', { ...data, ...language });
    }

    async onSubmit() {
        let url = 'index.php?route=cms/topic&language={{ language }}';

        var search = $('#input-search').val();

        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }

        var topic_id = $('#input-topic').prop('value');

        if (topic_id > 0) {
            url += '&topic_id=' + topic_id;
        }

        location = url;
    }

    onKeyDown() {
        if (e.keyCode == 13) {
            $('#button-search').trigger('click');
        }
    }
}