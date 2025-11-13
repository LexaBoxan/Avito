(function () {
  const editorCdn = 'https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js';

  function log(...args) {
    console.log('[CKEditor]', ...args);
  }

  function loadScript(src, callback) {
    if (document.querySelector(`script[src="${src}"]`)) {
      log('скрипт уже загружен');
      callback();
      return;
    }

    const script = document.createElement('script');
    script.src = src;
    script.onload = callback;
    script.onerror = () => console.error('Не удалось загрузить CKEditor по адресу', src);
    document.head.appendChild(script);
  }

  class LaravelUploadAdapter {
    constructor(loader, csrfToken) {
      this.loader = loader;
      this.csrfToken = csrfToken;
      this.controller = new AbortController();
    }

    upload() {
      return this.loader.file.then((file) => new Promise((resolve, reject) => {
        log('начали загрузку', file?.name, file?.size);
        const data = new FormData();
        data.append('upload', file);

        fetch('/upload-image', {
          method: 'POST',
          body: data,
          headers: {
            'X-CSRF-TOKEN': this.csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
          },
          credentials: 'same-origin',
          signal: this.controller.signal,
        })
          .then(async (response) => {
            if (!response.ok) {
              const text = await response.text();
              throw new Error(text || `Upload failed with status ${response.status}`);
            }
            return response.json();
          })
          .then((json) => {
            log('ответ /upload-image', json);
            if (json.url) {
              resolve({ default: json.url });
            } else if (json.error && json.error.message) {
              reject(json.error.message);
            } else {
              reject('Не удалось загрузить изображение.');
            }
          })
          .catch((error) => {
            console.error('Ошибка загрузки изображения', error);
            reject(error);
          });
      }));
    }

    abort() {
      this.controller.abort();
    }
  }

  function initEditors() {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta || !window.ClassicEditor) return;
    const csrf = csrfMeta.getAttribute('content');

    document.querySelectorAll('.js-ckeditor').forEach((textarea) => {
      if (textarea.dataset.editorInitialized === '1') {
        return;
      }

      log('инициализируем редактор', textarea);

      window.ClassicEditor.create(textarea, {
        toolbar: [
          'heading', '|',
          'bold', 'italic', 'link', '|',
          'bulletedList', 'numberedList', '|',
          'imageUpload', '|',
          'undo', 'redo',
        ],
        removePlugins: ['MediaEmbed', 'AutoMediaEmbed'],
        extraPlugins: [
          (editor) => {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => (
              new LaravelUploadAdapter(loader, csrf)
            );
          },
        ],
        image: {
          toolbar: [
            'imageStyle:inline',
            'imageStyle:block',
            'imageStyle:side',
            '|',
            'toggleImageCaption',
            'imageTextAlternative',
          ],
        },
      }).then(() => {
        log('инициализирован');
        textarea.dataset.editorInitialized = '1';
      }).catch((error) => console.error('CKEditor error', error));
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    log('DOMContentLoaded, загружаем CKEditor');
    loadScript(editorCdn, initEditors);
  });
})();
