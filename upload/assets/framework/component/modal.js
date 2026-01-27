import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Library
const template = await loader.library('template');
const currency = await loader.library('currency');

const template = `<div id="modal-security" class="modal show">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" slot="title"><i class="fa-solid fa-triangle-exclamation"></i> {{ heading_title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
     <div class="modal-header">

     
      </div>
    </div>
  </div>
</div>`;

class XModal extends WebComponent {
    async render(){
        let data = {};
        // Add the data attributes to the data object
        //this.data.id = this.getAttribute('data-id');
        data.heading_title = this.getAttribute('data-title');

        this.data.content = this.innerHTML;

        this.shadow.innerHTML = await this.render('modal.html', this.data);

        document.body.classList.add('modal-open');



        return
    }
}

customElements.define('x-modal', XModal);