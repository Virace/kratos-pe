<?php
// 获取post参数
function post_param($str)
{
    return !empty($_POST[$str]) ? $_POST[$str] : null;
}

if (!class_exists('WPMetaBox')):
    /**
     * Class WPMetaBox
     * https://github.com/waughjai/wp-meta-box
     *
     *
     */

    class WPMetaBox
    {
        //
        //  PUBLIC
        //
        /////////////////////////////////////////////////////////
        /*用法:
        new WPMetaBox
        (
            'test-meta',
            __('测试', 'kratos'),
            'post',
            [
                array(
                    'title'=> __('子项标题1', 'kratos'),
                    'key' => 'aaa',
                    'type' => 'textarea',
                    'desc' => __('这个说明只对textarea有效', 'kratos')
                ),
                array(
                    'title'=> __('子项标题2', 'kratos'),
                    'key' => 'bbb',
                    'type' => 'textarea',
                    'desc' => __('这个说明只对textarea有效', 'kratos')
                ),
                array(
                    'title' => __('子项标题3', 'VV'),
                    'key' => 'ccc',
                    'type' => 'select',
                    'values' =>
                        [
                            [ 'id' => '0', 'name' => 'Red' ],
                            [ 'id' => '1', 'name' => 'Blue' ],
                            [ 'id' => '2', 'name' => 'Green' ]
                        ]
                )
            ]
        );*/


        /**
         *
         * @var mixed|null
         * @var string $title
         * @var $post_type
         * @var array $items
         */

        public function __construct(string $slug, string $title, $post_type = ['page'], array $items = [])
        {
            $this->slug = $slug;
            $this->title = $title;
            $this->post_type = $post_type;
            $this->items = $items;

            // If singular, make it an array with singlular as only child.
            if (!is_array($this->post_type)) {
                $this->post_type = [$this->post_type];
            }

            // 增加对多字段支持
            if (!empty($this->items)) {
                $this->addActions();
            }

        }

        public function register(): void
        {
            add_meta_box
            (
                $this->slug,
                $this->title,
                [$this, 'drawGUI'],
                $this->post_type,
                'side',
                'high'
            );
        }

        public function drawGUI(WP_Post $post): void
        {
            $this->generateNonce();
            $this->drawInput($post);
        }

        public function save(int $post_id): void
        {
            if
            (
                !$this->testIsAutosaving() &&
                $this->testUserCanEdit($post_id) &&
                $this->testPostIsRightType() &&
                $this->testIsValidNonce()
            ) {
                foreach ($this->items as $item) {
                    $key = $this->slug . '-' . $item['key'];
                    update_post_meta($post_id, $key, (isset($_POST[$key])) ? $_POST[$key] : null);
                }
            }
        }

        public function getSlug(): string
        {
            return $this->slug;
        }

        public function getTitle(): string
        {
            return $this->title;
        }

        public function getValue($id, bool $singular = true)
        {
            if ($id === null) {
                $id = get_the_ID();
            }
            return get_post_meta($id, $this->slug, $singular);
        }

        public function getInputContent(WP_Post $post): string
        {
            ob_start();
            $this->drawInput($post);
            return ob_get_clean();
        }



        //
        //  PRIVATE
        //
        /////////////////////////////////////////////////////////

        private function addActions(): void
        {
            add_action('add_meta_boxes', [$this, 'Register']);
            add_action('save_post', [$this, 'Save']);
        }

        private function generateNonce(): void
        {
            wp_nonce_field(plugin_basename(__FILE__), $this->getTypeNonce());
        }

        private function drawInput(WP_Post $post): void
        {
            foreach ($this->items as $item) {
                ?>
                <div class="components-base-control">
                    <div class="components-base-control__field"><?php
                        switch ($item['type']) {
                            case ('textarea'):
                                {
                                    $this->printlabel($item);
                                    ?><textarea class="components-textarea-control__input" <?php $this->printCommonAttributes($item); ?>
                                                                                              rows="6"><?= $this->getPostValue($post, $item); ?></textarea><?php
                                }
                                break;

                            case ('checkbox'):
                                {
                                    $checked_text = ($this->getPostValue($post, $item) === '') ? '' : ' checked="true"';
                                    ?>
                                    <span class="components-checkbox-control__input-container">
                                    <input <?php $this->printTypeAttribute($item); ?><?php $this->printIDAttribute($item); ?><?php $this->printNameAttribute($item); ?>
                                size="100%"<?= $checked_text; ?>></span><?php
                                    $this->printlabel($item);
                                }
                                break;

                            case ('select'):
                                {
                                    $this->printlabel($item);
                                    if (isset($item['values']) && is_array($item['values'])) {
                                        $this->generateSelectInput($item, $post);
                                    }
                                }
                                break;


                            default:
                                {
                                    $this->printlabel($item);
                                    ?>
                                    <input <?php $this->printTypeAttribute($item); ?><?php $this->printCommonAttributes($item); ?>
                                    size="100%"
                                    value="<?= $this->getPostValue($post, $item); ?>"><?php
                                }
                                break;
                        } ?>      </div>
                </div><?php

            }


        }

        private function generateSelectInput(array $items, WP_Post $post): void
        {
            ?><select <?php $this->printIDAttribute($items); ?><?php $this->printNameAttribute($items); ?>><?php
            $values = $items['values'];
            if (is_array($values)) {
                foreach ($values as $value) {
                    if (isset($value['id']) && isset($value['name'])) {
                        $selected = $this->getPostValue($post, $items) == $value['id'];
                        $selected_text = ($selected) ? ' selected="selected"' : '';
                        ?>
                        <option
                        value="<?= $value['id']; ?>" label="<?= $value['name']; ?>"<?= $selected_text; ?>><?= $value['name']; ?></option><?php
                    }
                }
            }
            ?></select><?php
        }

        private function getTypeNonce(): string
        {
            return $this->slug . '-nonce-';
        }

        private function printlabel($item): void
        {
            if ($item['type'] === 'checkbox') {
                ?><label class="components-checkbox-control__label"
                         for="<?= $this->slug . '-' . $item['key'] ?>-input"><?= $item['title'] ?></label><?php
            } else {
                ?>
                <h3 style="font-size: 14px; padding: 9px 0; margin: 0; line-height: 1.4;"><?= $item['title'] ?></h3><?php
            }
        }

        private function printCommonAttributes($item): void
        {
            $this->printNameAttribute($item); ?><?php $this->printPlaceholderAttribute($item);
        }

        private function printIDAttribute($item): void
        {
            ?>id="<?= $this->slug . '-' . $item['key']; ?>-input"<?php
        }

        private function printNameAttribute($item): void
        {
            ?>name="<?= $this->slug . '-' . $item['key']; ?>"<?php
        }

        private function printPlaceholderAttribute($item): void
        {
            ?>placeholder="<?= ucwords($item['desc']); ?>"<?php
        }

        private function printTypeAttribute($item): void
        {
            ?>type="<?= $item['type']; ?>"<?php
        }

        private function getPostValue(WP_Post $post, $item): string
        {
            $value = get_post_meta($post->ID, $this->slug . '-' . $item['key'], true);
            return $value ? (string )($value) : '';
        }

        private function testIsAutosaving(): bool
        {
            return defined('DOING_AUTOSAVE') && DOING_AUTOSAVE;
        }

        private function testUserCanEdit(int $post_id): bool
        {
            return current_user_can('edit_post', $post_id);
        }

        private function testPostIsRightType(): bool
        {
            return isset($_POST['post_type']) && in_array($_POST['post_type'], $this->post_type);
        }

        private function testIsValidNonce(): bool
        {
            $nonce_id = $this->getTypeNonce();
            return isset($_POST[$nonce_id]) && wp_verify_nonce($_POST[$nonce_id], plugin_basename(__FILE__));
        }

        private string $slug;
        private string $title;
        private $post_type;
        private $items;
    }
endif;