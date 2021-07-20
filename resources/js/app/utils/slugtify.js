export function slugtify(title) {
  var title, slug;
  slug = title.toLowerCase();
  slug = slug.replace(/á|à|ạ|ả|ã|ă|ắ|ằ|ặ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
  slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
  slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
  slug = slug.replace(/o|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ộ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ợ|ỡ/gi, 'o');
  slug = slug.replace(/u|ú|ù|ủ|ụ|ũ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
  slug = slug.replace(/y|ý|ỳ|ỷ|ỵ|ỹ/gi, 'y');
  slug = slug.replace(/đ/gi, 'd');
  slug = slug.replace(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\_|\+|\=|\,|\.|\/|\?|\<|\>|\'|\"|\;|\:/gi,);
  slug = slug.replace(/ /gi, "-");
  slug = slug.replace(/\-\-\-\-\-/gi, "-");
  slug = slug.replace(/\-\-\-\-/gi, "-");
  slug = slug.replace(/\-\-\-/gi, "-");
  slug = slug.replace(/\-\-/gi, "-");
  slug = '@' + slug + '@';
  slug = slug.replace(/\@\-|\-\@|\@/gi, '');

  return slug;
}
