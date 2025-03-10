@include('header')
@include('blocks/navigation')
@include('blocks/page-title', {
"title": "Blog articles",
"description": "Our blog"
})

<section class="section blog-wrap">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        @@include('blocks/blog-posts.htm')
      </div>
      <div class="col-lg-4">
        @@include('blocks/post-sidebar.htm')
      </div>
    </div>
  </div>
</section>

@include('blocks/footer')
@include('footer')