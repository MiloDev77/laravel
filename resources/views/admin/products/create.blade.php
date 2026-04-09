<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form method="POST" action="/products" enctype="multipart/form-data">
        @csrf
        <input type="text" placeholder="Product name (en)" name="name_en" />
        <input type="text" placeholder="Product name (fr)" name="name_fr" />
        <input type="text" placeholder="Product Description (en)" name="description_en" />
        <input type="text" placeholder="Product Description (fr)" name="description_fr" />
        <input type="text" placeholder="GTIN Code" name="gtin" />
        <input type="text" placeholder="Brand Name" name="brand" />
        <select name="category_id">
            <option>Choose Category</option>
            @foreach ($categories as $category)
                @if (old('category_id') == $category->id)
                    <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                @else
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endif
            @endforeach
        </select>
        <select name="company_id">
            <option>Choose Company</option>
            @foreach ($companies as $company)
                @if (old('company_id') == $company->id)
                    <option value="{{ $company->id }}" selected>{{ $company->name }}</option>
                @else
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endif
                <option></option>
            @endforeach
        </select>
        <input type="text" placeholder="Country Origin" name="country" />
        <input type="text" placeholder="Gross Weight" name="gross_weight" />
        <input type="text" placeholder="Net Weight" name="net_weight" />
        <input type="text" placeholder="Unit Weight" name="unit_weight" />
        <input type="file" name="image_path" />
        <button type="submit">Send</button>
    </form>
</body>

</html>
