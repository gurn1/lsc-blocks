import { RichText } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const { question, answer } = attributes;

	return (
		<div className="lsc-faq-item">
			<RichText.Content
				tagName="h3"
				className="lsc-faq-question"
				value={question}
			/>

			<RichText.Content
				tagName="div"
				className="lsc-faq-answer"
				value={answer}
			/>
		</div>
	);
}