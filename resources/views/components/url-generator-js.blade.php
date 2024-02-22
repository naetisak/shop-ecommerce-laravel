<script>
    let url = "{!! request()->url() !!}";
    let full_url = new URL("{!! request()->fullUrl() !!}");
    let params = new URLSearchParams(full_url.search);

    const generateUrl = (object) => {
        for (const key in object) {
            if (object.hasOwnProperty.call(object, key)) {

                if (params.has(key)) params.delete(key);

                object[key] != null && object[key] != '' ? params.append(key, object[key]) : params.delete(key);
            }
        }
        return url + '?' + params.toString();
    };
</script>
