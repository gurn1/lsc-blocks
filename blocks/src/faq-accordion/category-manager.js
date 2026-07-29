import { useState } from '@wordpress/element';
import { TextControl, Button, PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

function slugify(text) {
  return text.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
}

export default function CategoryManager({ categories, setAttributes }) {
  const [newLabel, setNewLabel] = useState('');

  const addCategory = () => {
    const label = newLabel.trim();
    if (!label) return;

    const slug = slugify(label);
    if (categories.some((cat) => cat.slug === slug)) return;

    setAttributes({ categories: [...categories, { slug, label }] });
    setNewLabel('');
  };

  const removeCategory = (slug) => {
    setAttributes({ categories: categories.filter((cat) => cat.slug !== slug) });
  };

  return (
    <>
      {categories.map((cat) => (
        <div key={cat.slug} style={{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '4px' }}>
          <span style={{ flexGrow: 1 }}>{cat.label}</span>
          <Button isDestructive isSmall onClick={() => removeCategory(cat.slug)}>
            {__('Remove', 'lsc-blocks')}
          </Button>
        </div>
      ))}

      <TextControl
        label={__('Add category', 'lsc-blocks')}
        value={newLabel}
        onChange={setNewLabel}
        onKeyDown={(e) => {
          if (e.key === 'Enter') {
            e.preventDefault();
            addCategory();
          }
        }}
      />
      <Button variant="secondary" onClick={addCategory}>
        {__('Add category', 'lsc-blocks')}
      </Button>
    </>
  );
}