import './bootstrap';

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

document.addEventListener('DOMContentLoaded', () => {
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  document.querySelectorAll('.js-ckeditor').forEach((el) => {
    if (el._ck) return;

    ClassicEditor.create(el, {
      toolbar: [
        'heading', '|',
        'bold','italic','underline','link', '|',
        'bulletedList','numberedList', '|',
        'insertImage', '|',
        'undo','redo'
      ],
      removePlugins: ['MediaEmbed','AutoMediaEmbed'],  // не даём вставлять видео

      // Настраиваем загрузку файлов через CKFinder
      ckfinder: {
        uploadUrl: '/upload-image',
        headers: { 'X-CSRF-TOKEN': csrf }   // передаём токен Laravel
      },

      image: {
        toolbar: ['imageStyle:inline','imageStyle:block','imageStyle:side','|','toggleImageCaption','imageTextAlternative']
      }
    })
    .then(ed => el._ck = ed)
    .catch(console.error);
  });
});

