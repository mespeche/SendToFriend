# Module SendToFriend Thelia 2

This module allows your visitors to share a product with a friend by email.

## How to install

This module must be into your ```modules/``` directory (thelia/local/modules/).

You can download the .zip file of this module or create a git submodule into your project like this :

```
cd /path-to-thelia
git submodule add https://github.com/thelia-modules/SendToFriend.git local/modules/SendToFriend
```

Next, go to your Thelia admin panel for module activation.

## How to use

This module will provide a contact form already developed, you just need to built it into your template.

```html
{form name="front.sendToFriend.send"}
    <form action="{url path='/send-to-friend/send'}" method="post">
        {form_hidden_fields form=$form}

        {form_field form=$form field="return_url"}
        <input name="{$name}" type="hidden" value="{navigate to='current'}">
        {/form_field}

        {form_field form=$form field="product_id"}
        <input name="{$name}" type="hidden" value="{$ID}">
        {/form_field}

        {form_field form=$form field="email"}
        <div class="form-group">
            <label class="control-label" for="{$label_attr.for}">{$label} :</label>
            <input type="email" name="{$name}" class="form-control" id="{$label_attr.for}" autofocus="autofocus" {if $required} aria-required="true" required{/if}>
        </div>
        {/form_field}

        {form_field form=$form field="friend-email"}
        <div class="form-group">
            <label class="control-label" for="{$label_attr.for}">{$label} :</label>
            <input type="email" name="{$name}" class="form-control" id="{$label_attr.for}" {if $required} aria-required="true" required{/if}>
        </div>
        {/form_field}

        {form_field form=$form field="message"}
        <div class="form-group">
            <label class="control-label" for="{$label_attr.for}">{$label} :</label>
            <textarea name="{$name}" class="form-control" id="{$label_attr.for}" rows="5" {if $required} aria-required="true" required{/if}></textarea>
        </div>
        {/form_field}

        <button type="submit" class="btn btn-primary">{intl l="Send"}</button>

    </form>
{/form}
```