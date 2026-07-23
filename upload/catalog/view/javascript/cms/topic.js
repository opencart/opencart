import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('cms/topic');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        let topic = await loader.storage('cms/topic-' + request.get('topic_id'));

        if (topic !== undefined && config.config_language in article.description) {
            data.name = topic.name;
            data.description = topic.description;
            data.image = topic.image;

            data.articles = await loader.storage('cms/topic-' + this.getAttribute('topic_id'));



        }

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