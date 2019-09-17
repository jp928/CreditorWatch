<!DOCTYPE html>
<html lang="en">
  <header>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </header>
  <body>
    <div class="container-fluid mh-100">
      <form action="/" method="post">
          <div class="form-group">
            <label for="exampleInputEmail1">Keyword:</label>
            <input type="text" name="keyword" class="form-control" placeholder="keyword" />
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-primary">
          </div>
      </form>
      <p>Find indices are: [ %keyword% ]</p>
      <ul class="list-group">
      %content%
      </ul>
    </div>
  </body>
</html>
