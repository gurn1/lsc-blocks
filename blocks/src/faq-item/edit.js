import {
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
	const { question, answer } = attributes;

	return (
		<div {...useBlockProps({ className: 'lsc-faq-item' })}>
			<RichText
				tagName="h3"
				className="lsc-faq-question"
				value={question}
				onChange={(value) => setAttributes({ question: value })}
				placeholder={__('Add question…', 'lsc-blocks')}
				allowedFormats={['core/bold', 'core/italic']}
			/>

			<RichText
				tagName="div"
				className="lsc-faq-answer"
				value={answer}
				onChange={(value) => setAttributes({ answer: value })}
				placeholder={__('Add answer…', 'lsc-blocks')}
				allowedFormats={[
					'core/bold',
					'core/italic',
					'core/link',
				]}
			/>
		</div>
	);
}