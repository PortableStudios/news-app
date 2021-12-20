import styles from "../styles/Home.module.css";

export default function Card({ content }: any) {
	return (
		<div className={styles.card}>
			<div>{content}</div>
			<input type='checkbox' />
		</div>
	);
}
